<?php

namespace App\Form;

namespace App\Form;

use App\Entity\Solution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Reclamation;

class SolutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder       
          
        ->add('status', ChoiceType::class, [
            'choices' => [
                'Pending' => 'pending',
                'Accepted' => 'accepted',
                'Declined' => 'declined',
            ],
            'placeholder' => 'Choose a status',
        ])
        ->add('refundamount', NumberType::class, [
            'required' => false,
            'constraints' => [
                new Type([
                    'type' => 'float',
                    'message' => 'The refund amount must be a valid number.'
                ])
            ],
            'attr' => ['min' => 0], // Ensure only positive values can be entered if applicable
            'scale' => 2
        ])
        ->add('adminfeedback', TextareaType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Admin feedback is required.'
                ])
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solution::class,
        ]);
    }
}
