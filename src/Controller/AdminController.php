<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Form\ChambreFormType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function show_dashboard(EntityManagerInterface $entityManager): Response
    {

        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);

        return $this->render('admin/show_dashboard.html.twig', [
                'chambres' => $chambres,
        ]);
    } // end function show_dashboard


    #[Route('/ajouter-une-chambre', name: 'create_chambre', methods: ['GET', 'POST'])]
    public function createChambre(ChambreRepository $repository, Request $request, SluggerInterface $slugger): Response 
    {
        // Creation d'une nouvelle chambre
        $chambre = new Chambre();
        // Creation d'un formulaire chambre
        $form = $this->createForm(ChambreFormType::class, $chambre)
                ->handleRequest($request);

        // Condition de validation 
        if($form->isSubmitted() && $form->isValid()) {

            // champs datetime()
            $chambre->setCreatedAt(new DateTime());
            $chambre->setUpdatedAt(new DateTime());

            $chambre->setAlias($slugger->slug($chambre->getTitre()) );

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if($photo){
                $this->handleFile($photo, $slugger, $chambre);
            } // end if $photo


            $repository->add($chambre, true);

            $this->addFlash('success', "La chambre a bien été ajouté, en ligne !");
            return $this->redirectToRoute('show_dashboard');

        } // end if()

            return $this->render('admin/form/chambre.html.twig', [
                'chambre' => $chambre,
                'form' => $form->createView(),
            ]);

    } // end function createChambre

    private function handleFile(UploadedFile $photo, SluggerInterface $slugger, Chambre $chambre): void
    {
        $extension = '.' . $photo->guessExtension();
        $safeFilename = $slugger->slug($chambre->getTitre());

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $chambre->setPhoto($newFilename);
        } catch (FileException $exception) {
            // code à exécuter si erreur.
        }
    }// end function handleFile()

    #[Route('/modifier-une-chambre/{id}', name: 'update_chambre', methods: ['GET', 'POST'])]
    public function updateChambre(Chambre $chambre, Request $request, ChambreRepository $repository, SluggerInterface $slugger): Response
    {
        $originalPhoto = $chambre->getPhoto();

        $form = $this->createForm(ChambreFormType::class, $chambre, [
            'photo' => $originalPhoto
        ])->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
                $chambre->setUpdatedAt(new DateTime());
                $chambre->setAlias($slugger->slug($chambre->getTitre()) );

                    /** @var UploadedFile $photo */
                $photo = $form->get('photo')->getData();

                if($photo){
                    $this->handleFile($photo, $slugger, $chambre);
                }
                else {
                    $chambre->setPhoto($originalPhoto);
                }// end if $photo

                $repository->add($chambre, true);

                $this->addFlash('success', "La chambre a bien été Modifier, en ligne !");
                return $this->redirectToRoute('show_dashboard');

        } // end if()


        return $this->render('admin/form/chambre.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    } // end function updateChambre()

    #[Route('/archiver-une-chambre/{id}', name: 'soft_delete_chambre', methods: ['GET'])]
    public function softDeleteChambre(Chambre $chambre, ChambreRepository $repository): Response
    {

        $chambre->setDeletedAt(new DateTime());

        $repository->add($chambre, true);

        $this->addFlash('success', "La chambre a bien été archivé. Voir les archives !");
        return $this->redirectToRoute('show_dashboard');
    } // end softDeleteChambre()

    #[Route('/voir-les-archives', name: 'show_archives', methods: ['GET'])]
    public function showArchives(EntityManagerInterface $entityManager): Response
    {


        $chambres = $entityManager->getRepository(Chambre::class)->findAllArchived();

        return $this->render('admin/show_archives.html.twig', [
            'chambres' => $chambres
        ]);
    } // end showArchives()

    #[Route('/restaurer-un-article/{id}', name: 'restore_article', methods: ['GET'])]
    public function restoreArticle(Chambre $chambre, ChambreRepository $repository): RedirectResponse
    {
        $chambre->setDeletedAt(null);

        $repository->add($chambre, true);

        $this->addFlash('success', "La chambre a bien été restauré !");
        return $this->redirectToRoute('show_archives');
    }// end function restoreArticle()

    #[Route('/supprimer-une-chambre/{id}', name: 'hard_delete_chambre', methods: ['GET'])]
    public function hardDeleteChambre(Chambre $chambre, ChambreRepository $repository): RedirectResponse
    {

        $repository->remove($chambre, true);

        $this->addFlash('success', "La chambre a bien été supprimé définitivement du système !");
        return $this->redirectToRoute('show_archives');

    } // end hardDeleteChambre()


} // end class Admin
