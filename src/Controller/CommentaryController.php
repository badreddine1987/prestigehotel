<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaryController extends AbstractController
{

    #[Route('/ajouter-un-commentaire', name: 'add_commentaire', methods: ['GET', 'POST'])]
    public function addCommentaire(Request $request, CommentaireRepository $repository): Response
    {
        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireFormType::class, $commentaire)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentaire->setCreatedAt(new DateTime());
            $commentaire->setUpdatedAt(new DateTime());

            $repository->add($commentaire, true);
            $this->addFlash('success', "Votre commentaire a été ajouté avec succès ! ");
           
            return $this->redirectToRoute('show_commentaire');
        }

        return $this->render('commentary/form/form_commentaire.html.twig', [
            'form' => $form->createView(),

        ]);
    } //end function addCommentaire

    #[Route('/les-commentaires', name: 'show_commentaire', methods: ['GET'])]
    public function showCommentaire(CommentaireRepository $commentaireRepository): Response
    {
        $commentaires = $commentaireRepository->findBy(['deletedAt' => null]);


        return $this->render('commentary/show_commentaire.html.twig', [

            'commentaires' => $commentaires
        ]);
    } //end function showCommentaire()


    #[Route('/supprimer-le-commentaire/{id}', name: 'delete_commentaire', methods: ['GET'])]
    public function deleteCommentary(Commentaire $commentaire, CommentaireRepository $repository): RedirectResponse
    {
        $repository->remove($commentaire, true);

        $this->addFlash('success', 'Le commentaire a bien été supprimé ');
        return $this->redirectToRoute('show_commentaire', []);
    } //end function softDeleteCommentary()
}

