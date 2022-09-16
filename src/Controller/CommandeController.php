<?php

namespace App\Controller;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\CommandesFormType;
use App\Repository\ChambreRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/reserver-une-chambre/{id}', name: 'app_reservation', methods: ['GET', 'POST'])]
    public function addReservation(Chambre $chambre, Request $request, CommandeRepository $repository): Response
    { 

        $reservation = new Commande();
       
        $form = $this->createForm(CommandesFormType::class, $reservation)
            ->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

    
            $reservation->setChambre($chambre);
            $reservation->setCreatedAt(new DateTime);
            $reservation->setUpdatedAt(new DateTime);
            $reservation->setPrixTotal("20000"); //prix

         
            $repository->add($reservation, true);

            $this->addFlash('success', "La chambre a bien été réservé !");
            return $this->redirectToRoute('home', [
                'id'=> $chambre
            ]);
        }

        return $this->render('commande/form_reservation.html.twig', [
            'form' => $form->createView(),
            'commande' => $reservation,


        ]);
    }
}
