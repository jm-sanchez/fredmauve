<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_legal')]
    public function mentionsLegales(CartService $cartService): Response
    {
        return $this->render('legal/mentions_legales.html.twig', [
            'cart' => $cartService->getTotal()
        ]);
    }
    #[Route('/conditions-generales-de-vente', name: 'app_cgv')]
    public function conditionsGeneralesVente(CartService $cartService): Response
    {
        return $this->render('legal/conditions_generales_vente.html.twig', [
            'cart' => $cartService->getTotal()
        ]);
    }
    #[Route('/politique_de_confidentialite', name: 'app_pdc')]
    public function politiqueDonneesPersonnelles(CartService $cartService): Response
    {
        return $this->render('legal/politique_de_confidentialite.html.twig', [
            'cart' => $cartService->getTotal()
        ]);
    }
    #[Route('/credits', name: 'app_credits')]
    public function credits(CartService $cartService): Response
    {
        return $this->render('legal/credits.html.twig', [
            'cart' => $cartService->getTotal()
        ]);
    }
}
