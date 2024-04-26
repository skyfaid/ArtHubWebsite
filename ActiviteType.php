<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomAct')
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
            ->add('nbrePlaces')
            ->add('typeAct')
            ->add('posterUrl', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Poster Image : '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
