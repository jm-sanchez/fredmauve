<?php

namespace App\Controller;

use App\Form\CustomerFormType;
use App\Entity\Customer;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{

    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getTotal()
        ]);
    }

    #[Route('/cart/add/{id<\d+>}', name: 'cart_add')]
    public function addToCart(CartService $cartService, int $id): Response
    {
        $cartService->addToCart($id);
        $dataPaniers = $cartService->getTotal();
        // foreach ($dataPaniers as $dataPanier) {
        //     dd($dataPanier['work']);
        //     $work = $dataPanier['work'];
        // };
        $this->addFlash(
            'MessageFlash',
            'L\'article a bien été ajouté'
        );

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{id<\d+>}', name: 'cart_remove')]
    public function removeFromCart(CartService $cartService, int $id): Response
    {
        $cartService->removeFromCart($id);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/decrease/{id<\d+>}', name: 'cart_decrease')]
    public function decrease(CartService $cartService, $id): RedirectResponse
    {
        $cartService->decrease($id);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService): Response
    {
        $cartService->removeCartAll();
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/validate', name: 'validate')]

    public function addCustomer(
        CartService $cartService,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // récuperation du panier
        $dataPanier = $cartService->getTotal();
        //si le panier n'est pas vide on continue (différent de zéro)
        if (!$dataPanier == null) {
            $customer = new Customer(); // Créer une nouvelle instance de Customer

            $form = $this->createForm(CustomerFormType::class, $customer);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // // Récupérer les données du formulaire
                $customer = $form->getData();

                // Enregistrer le Customer en base de données
                $entityManager->persist($customer);
                $entityManager->flush();

                // stockage dans une session pour pouvoir l'utiliser dans le succès du paiement
                $customerId = $customer->getId();
                $request->getSession()->set('customerId', $customerId);

                // Rediriger vers la page de validation
                return $this->redirectToRoute('checkout');
            }
            //si le Panier est vide on redirige au panier (message votre Panier est vide)
        } else {
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/validate.html.twig', [
            'recapCart' => $cartService->getTotal(),
            'form' => $form->createView(),
            'cart' => $cartService->getTotal()
        ]);
    }
}
