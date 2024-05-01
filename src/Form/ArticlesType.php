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
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Title',
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Text',
                'attr' => ['style' => 'width: 100%;
                border: none;
                height: 60px;
                border: 1px solid #e5e5e5;
                color: #848484;
                outline: none;
                padding-left: 20px;
                margin-bottom: 30px;height: 165px;
                padding-top: 15px;'],
            ])
            ->add('imagePath', FileType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*', // Allow only image files
                    
                ],
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '5M',  // Adjust the max size as needed
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (jpeg, png, gif)',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [ // Add the submit button
                'label' => 'Submit Now',
                'attr' => ['class' => 'thm-btn contact-one__btn'],

            ]);;

        $builder->get('imagePath')
            ->addModelTransformer(new CallbackTransformer(
                function ($path) {
                    // transform the string back to a File object
                    return $path ? new File($path) : null;
                },
                function ($file) {
                    // transform the File object back to a string
                    return $file;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
