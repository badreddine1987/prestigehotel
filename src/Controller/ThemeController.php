<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    #[Route('/theme', name: 'show_restaurant', methods: ['GET'])]
    public function restaurant(): Response
    {
        return $this->render('thematique/show_restaurant.html.twig', [
           
        ]);
    }


}
