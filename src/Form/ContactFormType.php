<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
             $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
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
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 6],
            ])
            ->add('submit', SubmitType::class, [
            'label' => "Envoyer",
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
            // Configure your form options here
        ]);
    }
}
