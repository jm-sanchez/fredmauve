<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\AdminRepository;
use App\Repository\WorkRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/oeuvres')]
class WorkDashboardController extends AbstractController
{
    #[Route('/', name: 'admin_work_home', methods: ['GET'])]
    public function index(WorkRepository $workRepository): Response
    {
        return $this->render('dashboard/work_dashboard/index.html.twig', [
            'works' => $workRepository->findAll(),
        ]);
    }

    #[Route('/ajout', name: 'admin_work_add', methods: ['GET', 'POST'])]
    public function addWork(Request $request, EntityManagerInterface $entityManager, AdminRepository $adminRepository, PictureService $pictureService): Response
    {
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images
            $images = $form->get('images')->getData();

            foreach($images as $image){
                // On définit le dossier de destination
                $folder = 'works';

                // On appelle le service d'ajout d'image
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $work->addImage($img);
            }
            $admin = $adminRepository->findOneBy(["email" => "admin@admin.fr"]);
            $work->setAdministrator($admin);
            $entityManager->persist($work);
            $entityManager->flush();

            return $this->redirectToRoute('admin_work_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/work_dashboard/add.html.twig', [
            'work' => $work,
            'workForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_work_show', methods: ['GET'])]
    public function showWork(Work $work): Response
    {
        return $this->render('dashboard/work_dashboard/show.html.twig', [
            'work' => $work,
        ]);
    }

    #[Route('/{id}/modifier', name: 'admin_work_update', methods: ['GET', 'POST'])]
    public function updateWork(Request $request, Work $work, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_work_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/work_dashboard/update.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'admin_work_delete', methods: ['POST'])]
    public function deleteWork(Request $request, Work $work, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$work->getId(), $request->request->get('_token'))) {
            $entityManager->remove($work);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_work_home', [], Response::HTTP_SEE_OTHER);
    }
}
