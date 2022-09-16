<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Services\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerService $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();
            $subject = 'Demande de contact ' . $contactFormData['email'];
            $content = $contactFormData['nom'] . ' vous a envoyé le message suivant: ' . $contactFormData['message'];
            $mailer->sendEmail(subject: $subject, content: $content);
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
