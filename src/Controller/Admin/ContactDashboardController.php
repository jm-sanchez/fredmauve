<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/admin/messages", name="admin_contact_")
 */
class ContactDashboardController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('dashboard/contact_dashboard/index.html.twig', [
            'contact' => $contactRepository->findAll(),
        ]);
    }



    /**
     * @Route("/{id}", name="show")
     */
    public function showContact(Contact $contact): Response
    {
        return $this->render('dashboard/contact_dashboard/show.html.twig', [
            'contact' => $contact
        ]);
    }



    /**
     * @Route("/{id}/supprimer", name="delete")
     */
    public function deleteContact(ContactRepository $contactRepository, Contact $contact, EntityManagerInterface $em): Response
    {

            $contactRepository->remove($contact);

            $em->remove($contact);
            $em->flush();

            return $this->redirectToRoute('admin_contact_home');

    }


}