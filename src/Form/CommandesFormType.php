<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('dateDebut', DateType::class, [
        
                'label' => "Date d'arrivée",
                'widget' => "single_text",
               
           
            ])
            ->add('dateFin', DateType::class, [
        
                'label' => "Date de départ",
                'widget' => "single_text",
                
           
            ])

            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide.'
                    ]),
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide.'
                    ]),
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide.'
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide.'
                    ]),
                    new Email([
                        'message' => "l'email n'est pas au bon format ! Ex : mail@example.com"
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 255
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Réserver",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-12 btn btn-dark'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
