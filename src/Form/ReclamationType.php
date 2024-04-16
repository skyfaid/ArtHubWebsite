<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Oeuvre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('oeuvre', EntityType::class, [
            'class' => Oeuvre::class,
            'choice_label' => 'titre', 
        ])
        ->add('productPNG', FileType::class, [
            'label' => 'Product Image (JPG/PNG file)',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpeg',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid JPG or PNG image.',
                ])
            ],
        ])
        ->add('Description', TextareaType::class, [
            'label' => 'Description',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'The description must not be empty.',
                ])
            ],
        ])
    ;
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
