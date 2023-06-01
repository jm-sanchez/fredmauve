<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Repository\AdminRepository;
use App\Repository\NewsRepository;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\NewsType;
use Doctrine\ORM\EntityManagerInterface;

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
    public function addNews(NewsRepository $newsRepository, Request $request, AdminRepository $adminRepository, EntityManagerInterface $em): Response
    {
        $news = new News;

        $form = $this->createForm(NewsType::class, $news);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newsRepository->add($news);
            // $em = $this->getDoctrine()->getManager();
            
            $timezone = new DateTimeZone('Europe/Paris');
            $date = new \DateTime("now", $timezone);
            $news->setCreatedAt($date);
            $admin = $adminRepository->findOneBy(["id" => "2"]);
            $news->setAdministrator($admin);

            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('admin_news_home');
        }

        return $this->render('dashboard/news/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
