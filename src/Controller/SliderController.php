<?php

namespace App\Controller;

use DateTime;
use App\Entity\Slider;
use App\Form\SliderFormType;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
class SliderController extends AbstractController
{
    #[Route('/show-slider', name: 'show_slider', methods: ['GET'])]
    public function showSlider(EntityManagerInterface $entityManager): Response

    {
        $sliders = $entityManager->getRepository(Slider::class)->findBy(['deletedAt' => null]);
        return $this->render('admin/show_slider.html.twig', [
            'sliders' => $sliders
        ]);
    } //end function show_slider()

    #[Route('/ajouter-une-photo', name: 'add_photo', methods: ['GET', 'POST'])]
    public function addPhoto(Request $request, SluggerInterface $slugger, SliderRepository $repository): Response
    {
        $slider = new Slider;
        $form = $this->createForm(SliderFormType::class, $slider)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slider->setCreatedAt(new DateTime());
            $slider->setUpdatedAt(new DateTime());

            $photo = $form->get('photo')->getData();
            if ($photo) {
                $this->handleFile($photo, $slugger, $slider);
            } //end if

            $repository->add($slider, true);
            $this->addFlash('success', 'Photo ajoutée !');

            return $this->redirectToRoute('show_slider');
        }

        return $this->render('admin/form/slider.html.twig', [
            'form' => $form->createView(),
        ]);
    } //end function addPhoto()

    #[Route('/modifier-une-photo/{id}', name: 'update_photo', methods: ['GET', 'POST'])]
    public function updatePhoto(Slider $slider, Request $request, SliderRepository $repository, SluggerInterface $slugger): Response
    {
        $originalPhoto = $slider->getPhoto();

        $form = $this->createForm(SliderFormType::class, $slider,
        [
            'photo' => $originalPhoto
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slider->setUpdatedAt(new DateTime());

            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $slugger, $slider);
            } else {
                $slider->setPhoto($originalPhoto);
            } //end if $photo

            $repository->add($slider, true);
            $this->addFlash('success', 'Photo modifiée avec succès !');
            return $this->redirectToRoute('show_slider');
        } //endif form
        return $this->render('admin/form/slider.html.twig', [
            'form' => $form->createView(),
            'slider' => $slider
        ]);
    } //end function updatePhoto()


    #[Route('supprimer-un-photo/{id}', name: 'delete_photo', methods:['GET'])]
    public function softDeletePhoto(Slider $slider, SliderRepository $repository): RedirectResponse{

        $photo = $slider->getPhoto();

        if ($photo) {
            //Pour supprimer un fichier dans le système, on utilise la fonction native PHP unlink()
            unlink($this->getParameter('uploads_dir') . '/' . $photo);
        }

        $repository->remove($slider, true);

        $this->addFlash('success', 'slider supprimé définitivement du système :( !');
        return $this->RedirectToRoute('show_slider');
    }//end function hardDelete_slider()


    private function handleFile(UploadedFile $photo, SluggerInterface $slugger, Slider $slider): void
    {
        $extension = '.' . $photo->guessExtension();
        $safeFilename = $slugger->slug($slider->getPhoto());
        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {

            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $slider->setPhoto($newFilename);
        } catch (FileException $exception) {

            $this->addFlash('warning', 'La photo ne s\'est pas importée. Veuillez réessayer.');
        }
    }
}
