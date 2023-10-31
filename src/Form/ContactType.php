<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', 
                TextType::class, [
                'required' => true, 
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                       'message' => 'Veuillez saisir un nom'
                    ]),
                    new Length([
                       'min' => 6,
                       'minMessage' => 'Le nom doit contenir au minimum {{ limit }} caractères'
                    ])
                 ]
                ])
            ->add('firstname', 
                TextType::class, [
                'required' => true, 
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                       'message' => "Veuillez saisir un prénom"
                    ]),
                    new Length([
                       'min' => 6,
                       'minMessage' => "Le prénom doit contenir au minimum {{ limit }} caractères"
                    ]),
                 ]
                ])
            ->add('email', 
                EmailType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'E-mail'
                ])
            ->add('message', TextareaType::class, [
                'required' => true, 
                'label' => 'Message',
                'constraints' => [
                    new NotBlank([
                       'message' => 'Veuillez saisir votre message'
                    ]),
                    new Length([
                       'min' => 6,
                       'minMessage' => 'Le message doit contenir au minimum {{ limit }} caractères'
                    ]),
                 ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
