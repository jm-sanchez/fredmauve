<?php

namespace App\Service;

use App\Entity\Work;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    // Service pour accéder aux informations de la requête
    private RequestStack $requestStack;
    // Service pour interagir avec la base de données
    private EntityManagerInterface $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    // fonction d'incrémentaion ou d'ajout d'un article dans le panier.
    public function addToCart(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->getSession()->set('cart', $cart);
    }

    // fonction de décrementation ou suppréssion d'un article dans le panier.
    public function decrease(int $id)
    {
        $cart = $this->getSession()->get('cart', []);
        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }
        $this->getSession()->set('cart', $cart);
    }

    // Récupère le contenu du panier depuis la session et supprime l'article du panier à partir de l'id
    public function removeFromCart(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$id]);
        return $this->getSession()->set('cart', $cart);
    }

    //Supprime la totalité du panier en supprimant la clé de session.
    public function removeCartAll()
    {
        return $this->getSession()->remove('cart');
    }

    //Récupère les produits du panier avec leurs quantités et les retourne sous forme de tableau.
    public function getTotal(): array
    {
        $cart = $this->getSession()->get('cart');
        $cartData = [];

        if ($cart) {
            foreach ($cart as $id => $quantity) {
                $work = $this->em->getRepository(Work::class)->findOneBy(['id' => $id]);
                if ($work) {
                    $cartData[] = [
                        'work' => $work,
                        'quantity' => $quantity,
                    ];
                }
            }
            $this->getSession()->set('cart', $cart);
        }

        return $cartData;
    }

    // Méthode auxiliaire pour obtenir l'interface de la session
    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession(); // Obtient l'interface de la session
    }
}
