<?php

namespace App\Controller;

use App\Entity\Chambre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChambreController extends AbstractController
{
    #[Route('/chambre', name: 'app_chambre', methods: ['GET'])]
    public function chambre(EntityManagerInterface $entityManager): Response
    {

        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);

        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambres
        ]);
    } // chambre()

    #[Route('/chambre-classique', name: 'app_chambre_classique', methods: ['GET'])]
    public function chambreclassique(EntityManagerInterface $entityManager): Response
    {

        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['categorie' => 'c', 'deletedAt' => null]);

        return $this->render('chambre/classique.html.twig', [
                    'chambres' => $chambres
        ]);
    } // chambreclassique()

    #[Route('/chambre-confort', name: 'app_chambre_confort', methods: ['GET'])]
    public function chambreconfort(EntityManagerInterface $entityManager): Response
    {

        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['categorie' => 'confort', 'deletedAt' => null]);

        return $this->render('chambre/confort.html.twig', [
                    'chambres' => $chambres
        ]);
    } // chambreconfort()

    #[Route('/chambre-suite', name: 'app_chambre_suite', methods: ['GET'])]
    public function chambresuite(EntityManagerInterface $entityManager): Response
    {

        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['categorie' => 's', 'deletedAt' => null]);

        return $this->render('chambre/suite.html.twig', [
                    'chambres' => $chambres
        ]);
    } // chambreconfort()




}
