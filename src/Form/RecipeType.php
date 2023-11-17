<?php

namespace App\Form;

use App\Entity\Allergens;
use App\Entity\DietTypes;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
            'required' => true, 
            'label' => 'Nom',
            'constraints' => [
                new NotBlank([
                   'message' => "Veuillez saisir un nom"
                ]),
                new Length([
                   'min' => 6,
                   'minMessage' => "Le nom doit contenir au minimum {{ limit }} caractères"
                ]),
             ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true, 
                'label' => 'Description',
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
            ->add('preparationTime', TextareaType::class, [
                'required' => true, 
                'label' => 'Temps de préparation',
                'constraints' => [
                    new NotBlank([
                       'message' => 'Veuillez saisir votre temps de préparation'
                    ]),
                    new Length([
                       'min' => 6,
                       'minMessage' => 'Le message doit contenir au minimum {{ limit }} caractères'
                    ]),
                 ]
            ])
            ->add('break', TextareaType::class, [
                'required' => true, 
                'label' => 'Temps de repos',
                'constraints' => [
                    new NotBlank([
                       'message' => 'Veuillez saisir votre temps de repos'
                    ]),
                    new Length([
                       'min' => 1,
                       'minMessage' => 'Le message doit contenir au minimum {{ limit }} caractères'
                    ]),
                 ]
            ])
            ->add('cooking_time', TextType::class, [
                'required' => true, 
                'label' => 'Temps de cuisson',
                'constraints' => [
                    new NotBlank([
                       'message' => "Veuillez saisir un temps de cuisson"
                    ]),
                    new Length([
                       'min' => 6,
                       'minMessage' => "Le temps de cuisson doit contenir au minimum {{ limit }} caractères"
                    ]),
                 ]
                ])
            ->add('ingredients', TextareaType::class, [
                'required' => true, 
                'label' => 'Ingredients',
                'constraints' => [
                    new NotBlank([
                       'message' => 'Veuillez saisir les ingrédients'
                    ]),
                    new Length([
                       'min' => 6,
                       'minMessage' => 'Les ingrédients doit contenir au minimum {{ limit }} caractères'
                    ]),
                 ]
            ])
            ->add('steps', TextareaType::class, [
                'required' => true, 
                'label' => 'Etapes',
                'constraints' => [
                    new NotBlank([
                       'message' => 'Veuillez saisir les étapes'
                    ]),
                    new Length([
                       'min' => 6,
                       'minMessage' => 'Les étapes doit contenir au minimum {{ limit }} caractères'
                    ]),
                 ]
            ])
            ->add('imgFilename', FileType::class, [
                'label' => 'Image Recette',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une image au bon format png ou jpeg',
                    ])
                ],
            ])            
            ->add('allergens', EntityType::class, [
                'class' => Allergens::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('dietTypes', EntityType::class, [
                'class' => DietTypes::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
