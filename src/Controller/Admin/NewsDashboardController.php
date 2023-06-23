<?php

namespace App\Controller\Admin;

use App\Entity\ImageNews;
use App\Entity\News;
use App\Repository\AdminRepository;
use App\Repository\NewsRepository;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\NewsType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/admin/actualites')]
class NewsDashboardController extends AbstractController
{
    #[Route('/', name: 'admin_news_home', methods: ['GET'])]
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('dashboard/news_dashboard/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    #[Route('/ajout', name: 'admin_news_add', methods: ['GET', 'POST'])]
    public function addNews(NewsRepository $newsRepository, Request $request, AdminRepository $adminRepository, EntityManagerInterface $entityManagerInterface, PictureService $pictureService): Response
    {
        $news = new News;
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // On récupère les images
            $image = $form->get('images')->getData();

            if ($image) {

                // On définit le dossier de destination
                $folder = 'news';

                // On appelle le service d'ajout d'image
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new ImageNews();
                $img->setName($fichier);
                $news->setImageNews($img);
            }

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


    #[Route('/{id}', name: 'admin_news_show', methods: ['GET'])]
    public function showNews(News $news): Response
    {
        return $this->render('dashboard/news_dashboard/show.html.twig', [
            'news' => $news
        ]);
    }



    #[Route('/{id}/modifier', name: 'admin_news_update', methods: ['GET', 'POST'])]
    public function updateNews(Request $request, News $news, PictureService $pictureService, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // On récupère les images
            $image = $form->get('images')->getData();
            // On définit le dossier de destination
            $folder = 'news';

            // On appelle le service d'ajout d'image
            $fichier = $pictureService->add($image, $folder, 300, 300);

            $img = new ImageNews();
            $img->setName($fichier);
            $news->setImageNews($img);

            $entityManager->flush();

            return $this->redirectToRoute('admin_news_update', ['id' => $news->getId()], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('dashboard/news_dashboard/update.html.twig', [
            'form' => $form,
            'news' => $news
        ]);
    }


    #[Route('/{id}/supprimer', name: 'admin_news_delete', methods: ['POST'])]
    public function deleteNews(Request $request, News $news, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {

            $image = $news->getImageNews();

            // On récupère le nom de l'image
            $name = $image->getName();
            // dump($name);
            if ($pictureService->delete($name, 'news', 300, 300)){
                // On supprime l'image de la base de données
                $entityManager->remove($image);
                $entityManager->flush();
            }

            $entityManager->remove($news);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_news_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('image/{id}/supprimer', name: 'admin_delete_image_news', methods: ['DELETE'])]
    public function deleteImageNews(Request $request, ImageNews $imageNews, EntityManagerInterface $entityManager, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete'.$imageNews->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $name = $imageNews->getName();

            if ($pictureService->delete($name, 'news', 300, 300)){
                // On supprime l'image de la base de données
                $entityManager->remove($imageNews);
                $entityManager->flush();

                return new JsonResponse(['success' => true], 200);
            }

            // La suppression à échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
        // $response = new JsonResponse(array(
        //     'route' => 
        // ))
        // return $this->redirectToRoute('admin_work_update', ['id' => $work->getId()], Response::HTTP_SEE_OTHER);
    }


}
