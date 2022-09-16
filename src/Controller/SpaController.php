<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpaController extends AbstractController
{
    #[Route('/spa', name: 'show_spa', methods: ['GET'])]
    public function spa(): Response
    {
        return $this->render('thematique/show_spa.html.twig', [
           
        ]);
    }


}