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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private $gateway;
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->gateway = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    #[Route('cart/checkout', name: 'checkout')]

    public function checkout(CartService $cartService): Response
    {
        $cartItems = $cartService->getTotal();

        if (empty($cartItems)) {
            // Le panier est vide, effectuez une action appropriée (redirigez l'utilisateur vers la page du panier)
            return $this->redirectToRoute('cart');
        }

        $lineItems = [];
        $total = 0;

        foreach ($cartItems as $cartItem) {
            $work = $cartItem['work'];
            $quantity = $cartItem['quantity'];

            // Ajouter le produit avec la quantité au tableau des articles du panier
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $work->getTitle(),
                    ],
                    'unit_amount' => intval($work->getPrice() * 100),
                ],
                'quantity' => $quantity,
            ];

            $total += $work->getPrice() * $quantity; // Calculer le total des produits
        }

        // Créez la session de paiement avec les articles du panier et les autres paramètres
        $checkout = $this->gateway->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cart_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'consent_collection' => [
                'terms_of_service' => 'required',
            ],
        ]);

        // Enregistrez les détails de la commande dans la base de données
        return $this->redirect($checkout->url);
    }

    #[Route('/cart/success', name: 'checkout_success')]
    public function success(CartService $cartService, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, AdminRepository $admin): Response
    {

        //Récuperation des donnée du panier :
        $cartData = $cartService->getTotal();

        $customerId = $request->getSession()->get('customerId');

        // Récupérer le Customer depuis la session
        $customer = $entityManager->getRepository(Customer::class)->find($customerId);

        // Générer une référence unique pour la commande complète
        $orderReference = uniqid();

        // Initialisation de la variable pour pouvoir y accéder hors de la boucle foreach
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
            $ordersDetails->setReference($orderReference); // Utiliser la même référence pour tous les produits du panier
            $ordersDetails->setQuantity($quantity);
            $ordersDetails->setPrice($work->getPrice() * $quantity);
            $ordersDetails->setCustomer($customer);
            $ordersDetails->setWork($work);

            // Persist et flush de l'objet Orders pour chaque produit du panier
            $entityManager->persist($ordersDetails);
            $entityManager->flush();

            $allItemsFromOrder[] = $ordersDetails;
        }

        // Récuperation de l'heure et la date actuelle :
        $dateTime = new DateTime();
        // Récuperation du montant total de la commande pour l'afficher sur les emails
        $totalPrice = 0;
        foreach ($allItemsFromOrder as $orderDetails) {
            $totalPrice += $orderDetails->getPrice();
        }
        // Supprimer le contenu du panier
        $cartService->removeCartAll();
        // Email de l'admin
        // $admin = $admin->findOneBy(['roles' => ["ROLE_ADMIN"]]); //CECI NE MARCHE PAS
        $admin = $admin->findOneBy(['id' => 1]);
        $admin_email = $admin->getEmail();

        // Email de confirmation de la commande à l'administrateur
        $emailToAdmin = (new TemplatedEmail())
            ->from($customer->getEmail())
            ->to($admin_email)
            ->subject('Félicitations ! Une commande a été confirmée ! (REFERENCE N° ' . $ordersDetails->getReference() . ')')
            ->htmlTemplate('emails/order-admin.html.twig')
            ->context([
                'order' => $allItemsFromOrder,
                'customer' => $customer,
                'totalPrice' => $totalPrice,
            ]);

        // Email de confirmation de la commande au client
        $emailToClient = (new TemplatedEmail())
            ->from($admin_email)
            ->to($customer->getEmail())
            ->subject('Merci! Votre commande a été validée ! (REFERENCE N° ' . $ordersDetails->getReference() . ')')
            ->htmlTemplate('emails/order-customer.html.twig')
            ->context([
                'order' => $allItemsFromOrder,
                'customer' => $customer,
                'admin_email' => $admin_email,
                'totalPrice' => $totalPrice,
            ]);

        $mailer->send($emailToAdmin);
        $mailer->send($emailToClient);


        // Passez les informations nécessaires au template
        return $this->render('cart/success.html.twig', [
            'ordersDetails' => $ordersDetails,
            'customer' => $customer,
            'dateTime' => $dateTime,
            'cart' => $cartService->getTotal(),
        ]);
    }
}
