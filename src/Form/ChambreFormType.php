<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChambreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Classe',
                'expanded' => 'true',
                'choices' => [
                    'Classique' => 'c',
                    'Confort' => 'confort',
                    'Suite' => 's'
                ]
                ])
            ->add('descriptionCourte', TextType::class, [
                'label' => 'Présentation général'
            ])
            ->add('descriptionLong',  TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('photo', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
            ])
            ->add('prixJournalier')
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-6 btn btn-primary',
                ],
            ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
            'allow_file_upload' => true,
            'photo' => null
        ]);
    }
}
