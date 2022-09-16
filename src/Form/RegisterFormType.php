<?php

namespace App\Form;

use App\Entity\Membre;
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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('password', PasswordType::class, [
            'label' => "Choisissez un mot de passe",
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide.'
                ]),
                new Length([
                    'min' => 4,
                    'max' => 255
                ])
            ]
        ])
            ->add('nom', TextType::class, [
                'label' => "Votre nom",
                'constraints' => [
                    new NotBlank([
                    'message' => 'Ce champs ne peut être vide.'
                ]),
                ]
            ])
            ->add('prenom', TextType::class, [
            'label' => "Votre prénom",
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide.'
                ]),
            ]
        ])
            ->add('civilite', ChoiceType::class, [
            'label' => 'Civilité',
            'expanded' => 'true',
            'choices' => [
                'Homme' => 'h',
                'Femme' => 'f',
                'Non-binaire' => 'nb'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide !'
                ]),
            ],
        ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto mmy-3 col-4 btn btn-dark'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
