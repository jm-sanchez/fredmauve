<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/actualites", name="admin_news_")
 */
class NewsDashboardController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('dashboard/news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="add")
     */
    public function addNews(Request $request)
    {
        $news = new News;

        $form = $this->createForm(NewsType::class, $news);

        $form->handleRequest($request);

        // if($form->isSubmitted() && $form-->isValid()){
        //     $em = $this->getDoctrine()getManager();
        //     $em->persist($news);
        //     $em->flush();

        //     return $this->redirectToRoute('admin_home');
        // }

        return $this->render('admin/actualites/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
