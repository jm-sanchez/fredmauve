<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\AdminRepository;
use App\Service\CartService;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, EntityManagerInterface $em, AdminRepository $adminRepository, CartService $cartService, Recaptcha3Validator $recaptcha3Validator): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Filtration des variables
            $name = filter_var($form->get("name")->getData(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var($form->get("email")->getData(), FILTER_SANITIZE_EMAIL);
            $subject = filter_var($form->get("subject")->getData(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $message = filter_var($form->get("message")->getData(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // Définition de la date demande
            $timezone = new DateTimeZone('Europe/Paris');
            $date = new \DateTime("now", $timezone);
            // Vérification de l'email de l'admin
            $admin = $adminRepository->findOneBy(["email" => "admin@admin.fr"]);
            // Obtention du score reCAPTCHA v3
            $score = $recaptcha3Validator->getLastResponse()->getScore();

            $contact->setName($name)
                ->setEmail($email)
                ->setSubject($subject)
                ->setMessage($message)
                ->setCreatedAt($date)
                ->setAdministrator($admin);

            if ($score >= 0.7) {
                $em->persist($contact);
                $em->flush();
                // Message flash
                $this->addFlash('notice', 'Votre message a été envoyé !');
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'formContact' => $form->createView(),
            'cart' => $cartService->getTotal(),
        ]);
    }
}

