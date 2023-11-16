<?php

namespace App\Controller;

use App\Service\CartService;
use App\Repository\WorkRepository;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="app_shop")
     */
    public function index(WorkRepository $workRepository, ImageRepository $imageRepository, CartService $cartService): Response
    {
        $saleableWorks = $workRepository->findBy(array('saleable' => 1));
        return $this->render('shop/index.html.twig', [
            'saleableWorks' => $saleableWorks,
            'images' => $imageRepository->findAll(),
            'cart' => $cartService->getTotal(),
        ]);
    }
}
