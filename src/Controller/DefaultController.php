<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('default/home.html.twig');
    }

    #[Route('/mentions-légales', name: 'mentionslegales', methods: ['GET'])]
    public function mentions(): Response
    {
        return $this->render('otherlinks/mentionslegales.html.twig', [
           
        ]);
    }

    #[Route('/conditions-général-de-vente', name: 'cgv', methods: ['GET'])]
    public function cgv(): Response
    {
        return $this->render('otherlinks/cgv.html.twig', [
           
        ]);
    }

    #[Route('/plan-du-site', name: 'plan', methods: ['GET'])]
    public function plan(): Response
    {
        return $this->render('otherlinks/plan.html.twig', [
           
        ]);
    }
}
