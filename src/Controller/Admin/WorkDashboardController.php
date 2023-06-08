<?php

namespace App\Controller\Admin;

use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\AdminRepository;
use App\Repository\WorkRepository;
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
    public function addWork(Request $request, EntityManagerInterface $entityManager, AdminRepository $adminRepository): Response
    {
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $adminRepository->findOneBy(["roles" => '["ROLE_ADMIN"]']);
            $work->setAdministrator($admin);
            $entityManager->persist($work);
            $entityManager->flush();

            return $this->redirectToRoute('admin_work_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/work_dashboard/add.html.twig', [
            'work' => $work,
            'form' => $form,
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
