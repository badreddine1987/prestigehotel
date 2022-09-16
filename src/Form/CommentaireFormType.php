<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide.'
                    ]),
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
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
            ->add('categorie', ChoiceType::class, [
                'label' => 'Sujet',

                'choices' => [
                    'Hotel' => 'hotel',
                    'Chambre' => 'chambre',
                    'Restaurant' => 'restaurant',
                    'Spa' => 'spa',
                    'Service' => 'service',

                ],
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide.'
                    ]),
                ]
            ])


            ->add('commentaire', TextareaType::class, [
                'attr' => ['rows' => 5],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide.'
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto mmy-3 col-4 btn btn-dark'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
