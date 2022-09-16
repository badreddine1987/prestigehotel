<?php

namespace App\Controller;
use DateTime;
use App\Entity\Slider;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RenderController extends AbstractController
{
    #[Route('/carousel-in-nav', name: 'carousel_in_nav', methods:['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sliders = $entityManager->getRepository(Slider::class)->findBy(['deletedAt' => null]);
        return $this->render('rendered/carousel_in_nav.html.twig', [
            'sliders' => $sliders
        ]);
    }  
}
