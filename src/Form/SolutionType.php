<?php

namespace App\Form;
use App\Entity\Solution;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
                'attr' => ['min' => 0], 
                'scale' => 2
            ])
            ->add('adminfeedback', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Admin feedback is required.'
                    ]),
     
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Admin feedback must be at least {{ limit }} characters long.',
                    ]),
                ]
            ]);

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                
                if (isset($data['status']) && $data['status'] === 'declined') {
                    $form->get('refundamount')->setData(0.00);
                    $form->add('refundamount', NumberType::class, [
                        'required' => false,
                        'disabled' => true
                    ]);
                }
            });
        }
        

        public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Solution::class,
        'validation_groups' => function (FormInterface $form) {
            $data = $form->getData();
            if ($data->getStatus() === 'accepted') {
                return ['Default', 'active_refund'];
            }
            return ['Default'];
            
        },
    ]);
}}
