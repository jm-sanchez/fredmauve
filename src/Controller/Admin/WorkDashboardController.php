<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\AdminRepository;
use App\Repository\ImageRepository;
use App\Repository\WorkRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/oeuvres")
 */
class WorkDashboardController extends AbstractController
{
    /**
     * @Route("/", name="admin_work_home", methods={"GET"})
     */
    public function index(WorkRepository $workRepository): Response
    {
        return $this->render('dashboard/work_dashboard/index.html.twig', [
            'works' => $workRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="admin_work_add", methods={"GET", "POST"})
     */
    public function addWork(Request $request, EntityManagerInterface $em, AdminRepository $adminRepository, PictureService $pictureService): Response
    {
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récuupération des images
            $images = $form->get('images')->getData();
            foreach($images as $image){
                // Définition du dossier de destination
                $folder = 'works';
                // Appel du service d'ajout d'image
                $fichier = $pictureService->add($image, $folder, 300, 300);
                $img = new Image();
                $img->setName($fichier);
                $work->addImage($img);
            }
            $admin = $adminRepository->findOneBy(["email" => "admin@admin.fr"]);
            $work->setAdministrator($admin);
            $em->persist($work);
            $em->flush();

            return $this->redirectToRoute('admin_work_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/work_dashboard/add.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_work_show", methods={"GET"})
     */
    public function showWork(Work $work): Response
    {
        return $this->render('dashboard/work_dashboard/show.html.twig', [
            'work' => $work,
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="admin_work_update", methods={"GET", "POST"})
     */
    public function updateWork(Request $request, Work $work, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récuupération des images
            $images = $form->get('images')->getData();
            foreach($images as $image){
                // Définition du dossier de destination
                $folder = 'works';
                // Appel du service d'ajout d'image
                $fichier = $pictureService->add($image, $folder, 300, 300);
                $img = new Image();
                $img->setName($fichier);
                $work->addImage($img);
            }
            $em->persist($work);
            $em->flush();

            return $this->redirectToRoute('admin_work_home');
        }

        return $this->renderForm('dashboard/work_dashboard/update.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/supprimer", name="admin_work_delete", methods={"POST"})
     */
    public function deleteWork(Request $request, Work $work, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$work->getId(), $request->request->get('_token'))) {
            $images = $work->getImages();
            foreach($images as $image){
                // Récupèreation du nom de l'image
                $name = $image->getName();
                if ($pictureService->delete($name, 'works', 300, 300)){
                // Suppression de l'image de la base de données
                    $em->remove($image);
                    $em->flush();
                }
            }
            $em->remove($work);
            $em->flush();
        }

        return $this->redirectToRoute('admin_work_home', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("image/{id}/supprimer", name="admin_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Request $request, Image $image, EntityManagerInterface $entityManager, PictureService $pictureService): JsonResponse
    {
        // Récupération du contenu de la requête
        $data = json_decode($request->getContent(), true);
        // Validation du token csrf
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // Récupération du nom de l'image
            $name = $image->getName();
            if ($pictureService->delete($name, 'works', 300, 300)){
                // Suppression de l'image de la base de données
                $entityManager->remove($image);
                $entityManager->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // Si la suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }
        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
}
