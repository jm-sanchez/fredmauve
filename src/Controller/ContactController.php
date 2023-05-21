<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = filter_var($form->get("name")->getData(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var($form->get("email")->getData(), FILTER_VALIDATE_EMAIL);
            $message = filter_var($form->get("message")->getData(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $date = new \DateTime();
            if ($name) {
                $form->get("name")->getData();
            }
            if ($email) {
                $form->get("email")->getData();
            }
            if ($message) {
                $form->get("message")->getData();
            }
            $contact->setCreatedAt($date);
            
            $manager->persist($contact);
            $manager->flush();
            // Message flash
            $this->addFlash(
                'notice',
                'Votre message a été envoyé !'
            );
            return $this->redirectToRoute('app_home');
        }


        return $this->render('contact/index.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contact/success", name="contact_success")
     */
    public function successContact(): Response
    {
        return $this->render("contact/success.html.twig");
    }
}

