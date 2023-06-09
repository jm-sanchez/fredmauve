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
        return $this->render('dashboard/news_dashboard/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="add")
     */
    public function addNews(NewsRepository $newsRepository, Request $request, AdminRepository $adminRepository): Response
    {
        $news = new News;
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $admin = $adminRepository->findOneBy(["email" => "admin@admin.fr"]);
            $news->setAdministrator($admin);
            $timezone = new DateTimeZone('Europe/Paris');
            $date = new \DateTime("now", $timezone);
            $news->setCreatedAt($date);
            // La méthode "add" est l'équivalent de persist et flush (elle se trouve dans le NewsRepository)
            // Attention, le 'true' est nécessaire pour que le flush se fasse.
            $newsRepository->add($news, true);
            // $em = $this->getDoctrine()->getManager();

            // $em->persist($news);
            // $em->flush();

            return $this->redirectToRoute('admin_news_home');
        }

        return $this->render('dashboard/news_dashboard/add.html.twig', [
            'newsForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function showNews(News $news): Response
    {
        return $this->render('dashboard/news_dashboard/show.html.twig', [
            'news' => $news
        ]);
    }


    /**
     * @Route("/{id}/modifier", name="update")
     */
    public function updateNews(NewsRepository $newsRepository, Request $request, News $news): Response
    {

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newsRepository->add($news, true);

            // $em->persist($news);
            // $em->flush();

            return $this->redirectToRoute('admin_news_home');
        }
        return $this->render('dashboard/news_dashboard/update.html.twig', [
            'newsForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}/supprimer", name="delete")
     */
    public function deleteNews(NewsRepository $newsRepository, News $news): Response
    {

            $newsRepository->remove($news, true);

            // $em->remove($news);
            // $em->flush();

            return $this->redirectToRoute('admin_news_home');

    }


}
