<?php

namespace App\Controller;

use App\Form\NewsletterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'newsletter', methods: ['GET'])]
    public function newsletter(Request $request): Response
    {

        $form = $this->createForm(NewsletterFormType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $this->addFlash('success', 'Vous êtes bien inscrit à la newsletter');
        return $this->redirectToRoute('home');
        }

        return $this->render('otherlinks/newsletter.html.twig', ['form' => $form->createView()]);
    }
}
