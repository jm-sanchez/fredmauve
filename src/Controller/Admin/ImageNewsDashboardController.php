<?php

namespace App\Controller\Admin;

use App\Entity\ImageNews;
use App\Form\ImageNewsType;
use App\Repository\ImageNewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image/news/dashboard')]
class ImageNewsDashboardController extends AbstractController
{
    #[Route('/', name: 'app_image_news_dashboard_index', methods: ['GET'])]
    public function index(ImageNewsRepository $imageNewsRepository): Response
    {
        return $this->render('image_news_dashboard/index.html.twig', [
            'image_news' => $imageNewsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_image_news_dashboard_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImageNewsRepository $imageNewsRepository): Response
    {
        $imageNews = new ImageNews();
        $form = $this->createForm(ImageNewsType::class, $imageNews);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageNewsRepository->add($imageNews, true);

            return $this->redirectToRoute('app_image_news_dashboard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('image_news_dashboard/new.html.twig', [
            'image_news' => $imageNews,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_news_dashboard_show', methods: ['GET'])]
    public function show(ImageNews $imageNews): Response
    {
        return $this->render('image_news_dashboard/show.html.twig', [
            'image_news' => $imageNews,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_news_dashboard_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImageNews $imageNews, ImageNewsRepository $imageNewsRepository): Response
    {
        $form = $this->createForm(ImageNewsType::class, $imageNews);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageNewsRepository->add($imageNews, true);

            return $this->redirectToRoute('app_image_news_dashboard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('image_news_dashboard/edit.html.twig', [
            'image_news' => $imageNews,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_news_dashboard_delete', methods: ['POST'])]
    public function delete(Request $request, ImageNews $imageNews, ImageNewsRepository $imageNewsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imageNews->getId(), $request->request->get('_token'))) {
            $imageNewsRepository->remove($imageNews, true);
        }

        return $this->redirectToRoute('app_image_news_dashboard_index', [], Response::HTTP_SEE_OTHER);
    }
}
