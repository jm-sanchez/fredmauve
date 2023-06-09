<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\AdminRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class CategoryDashboardController extends AbstractController
{
    #[Route('/', name: 'admin_category_home', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('dashboard/category_dashboard/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/ajout', name: 'admin_category_add', methods: ['GET', 'POST'])]
    public function addCategory(Request $request, CategoryRepository $categoryRepository, AdminRepository $adminRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $adminRepository->findOneBy(["email" => "admin@admin.fr"]);
            $category->setAdministrator($admin);
            $categoryRepository->add($category, true);
            return $this->redirectToRoute('admin_category_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/category_dashboard/add.html.twig', [
            'category' => $category,
            'categoryForm' => $form,
        ]);
    }

    // pas besoin de show pour le catégorie
    // #[Route('/{id}', name: 'admin_category_show', methods: ['GET'])]
    // public function showCategory(Category $category): Response
    // {
    //     return $this->render('dashboard/category_dashboard/show.html.twig', [
    //         'category' => $category,
    //     ]);
    // }

    #[Route('/{id}/modifier', name: 'admin_category_update', methods: ['GET', 'POST'])]
    public function updateCategory(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('admin_category_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/category_dashboard/update.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'admin_category_delete', methods: ['POST'])]
    public function deleteCategory(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('admin_category_home', [], Response::HTTP_SEE_OTHER);
    }
}
