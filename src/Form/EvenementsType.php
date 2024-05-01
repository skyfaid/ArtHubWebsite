<?php

namespace App\Form;

use App\Entity\Evenements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\File as FileConstraint;


class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('datedebut', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => new \DateTime('today'),
                        'message' => 'Event start date must be today or in the future.',
                    ]),
                ],
                'attr' => [
                    'min' => date('Y-m-d')  // Set the minimum date to today
                ],
                'invalid_message' => 'Please provide a valid date and time.'
            ])
            
            ->add('datefin', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('lieu')
            ->add('type')
            ->add('description')
            ->add('nombreplaces')
            ->add('posterurl', FileType::class, [
                'attr' => [
                    'accept' => 'image/jpeg,image/png,image/gif', // Accepts JPEG, PNG, GIF
                ],
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (jpeg, png, gif)',
                    ]),
                ],
            ])
            ->add('videourl', FileType::class, [
                'attr' => [
                    'accept' => 'video/mp4,video/x-matroska', // Accepts MP4, MKV
                ],
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'video/mp4',
                            'video/quicktime',
                            'video/x-msvideo',
                            'video/x-matroska',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid video file (mp4, mov, avi, mkv)',
                    ]),
                ],

            ])
            ->add('isExclusive', CheckboxType::class, [
                'label'    => 'Event Exclusivity',
                'required' => false,
            ]);
            
            

        $builder->get('posterurl')
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

        $builder->get('videourl')
        ->addModelTransformer(new CallbackTransformer(
            function ($path) {
                return $path ? new File($path) : null;
            },
            function ($file) {
                return $file;
            }
        ));
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenements::class,
        ]);
    }
}
