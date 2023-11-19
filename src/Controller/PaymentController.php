<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\OrderDetails;
use App\Repository\AdminRepository;
use App\Service\CartService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
    }

    #[Route('/cart/checkout', name: 'checkout')]
    public function checkout(CartService $cartService): Response
    {
        $lineItems = $this->addItemsToCheckout($cartService);

        // Création de la session de paiement
        $checkout = $this->stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cart_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'consent_collection' => [
                'terms_of_service' => 'required',
            ],
        ]);

        return $this->redirect($checkout->url);
    }

    private function addItemsToCheckout(CartService $cartService): array {
        $cartItems = $cartService->getTotal();
        $itemsForCheckout = [];

        foreach ($cartItems as $cartItem) {
            $work = $cartItem['work'];
            $quantity = $cartItem['quantity'];

            // Ajout des produits du panier
            $itemsForCheckout[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $work->getTitle(),
                    ],
                    'unit_amount' => $work->getPrice() * 100,
                ],
                'quantity' => $quantity,
            ];
        }
        return $itemsForCheckout;
    }


    #[Route('/cart/success', name: 'checkout_success')]
    public function success(CartService $cartService, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, AdminRepository $admin): Response
    {
        $cartData = $cartService->getTotal();
        $customerId = $request->getSession()->get('customerId');
        // Récupérer le Customer depuis la session
        $customer = $entityManager->getRepository(Customer::class)->find($customerId);
        $orderReference = uniqid();
        $ordersDetails = null;
        // Initialisation d'un tableau où seront ajoutés tous les produits de la commande
        $allItemsFromOrder = [];

        // Parcourir les produits du panier et enregistrer les détails dans la base de données
        foreach ($cartData as $cartProduct) {
            $work = $cartProduct['work'];
            $quantity = $cartProduct['quantity'];

            // Instanciation d'un nouvel objet Ordersdetails pour chaque produit du panier
            $ordersDetails = new OrderDetails();
            $ordersDetails->setCreatedAt(new DateTime('now'));
            $ordersDetails->setReference($orderReference); // Même référence pour tous les produits
            $ordersDetails->setQuantity($quantity);
            $ordersDetails->setPrice($work->getPrice() * $quantity);
            $ordersDetails->setCustomer($customer);
            $ordersDetails->setWork($work);

            $entityManager->persist($ordersDetails);
            $entityManager->flush();

            $allItemsFromOrder[] = $ordersDetails;
        }

        // Suppression du contenu du panier
        $cartService->removeCartAll();

        // Mails de confirmation de commande
        $this->sendConfirmationEmails($allItemsFromOrder, $mailer, $admin, $entityManager, $request);

        return $this->render('cart/success.html.twig', [
            'ordersDetails' => $ordersDetails,
            'customer' => $customer,
            'cart' => $cartService->getTotal(),
        ]);
    }

    private function sendConfirmationEmails(array $allItemsFromOrder, MailerInterface $mailer, AdminRepository $admin, EntityManagerInterface $entityManager, Request $request): void {
        $customerId = $request->getSession()->get('customerId');
        $customer = $entityManager->getRepository(Customer::class)->find($customerId);
        $admin = $admin->findOneBy(['id' => 1]);

        $orderReference = $allItemsFromOrder[0]->getReference();

        $emailToAdmin = (new TemplatedEmail())
            ->from($customer->getEmail())
            ->to($admin->getEmail())
            ->subject('Félicitations ! Une commande a été confirmée ! (REFERENCE N° ' . $orderReference . ')')
            ->htmlTemplate('emails/order-admin.html.twig')
            ->context([
                'order' => $allItemsFromOrder,
                'customer' => $customer,
                'totalPrice' => $this->calculateTotalPrice($allItemsFromOrder),
            ]);

        $emailToClient = (new TemplatedEmail())
            ->from($admin->getEmail())
            ->to($customer->getEmail())
            ->subject('Merci! Votre commande a été validée ! (REFERENCE N° ' . $orderReference . ')')
            ->htmlTemplate('emails/order-customer.html.twig')
            ->context([
                'order' => $allItemsFromOrder,
                'customer' => $customer,
                'admin_email' => $admin->getEmail(),
                'totalPrice' => $this->calculateTotalPrice($allItemsFromOrder),
            ]);

        $mailer->send($emailToAdmin);
        $mailer->send($emailToClient);
    }

    private function calculateTotalPrice(array $allItemsFromOrder): int {
        $totalPrice = 0;
        foreach ($allItemsFromOrder as $orderDetails) {
            $totalPrice += $orderDetails->getPrice();
        }
        return $totalPrice;
    }
}
