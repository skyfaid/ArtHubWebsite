<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', TextType::class, [
            'label' => 'Title',
        ])
        ->add('contenu', TextareaType::class, [
            'label' => 'Text(required)',
        ])
        ->add('imagePath', TextType::class, [
            'label' => 'Image',
            'required' => false,
            /*'constraints' => [
                new File([
                   
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/jpg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid JPEG or JPG image file',
                ]),
            ],*/
        ])
        ->add('submit', SubmitType::class, [ // Add the submit button
            'label' => 'Submit Now',
            'attr' => ['class' => 'thm-btn contact-one__btn'],
            
        ]);
        ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
