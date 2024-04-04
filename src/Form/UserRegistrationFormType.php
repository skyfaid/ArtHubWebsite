<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormType extends AbstractType
{
    private function getCountryChoices()
    {

        return [

            '🇫🇷 France +33' => '33',
            '🇩🇪 Germany +49' => '49',
            '🇮🇹 Italy +39' => '39',
            '🇯🇴 Jordan +962' => '962',
            '🇲🇦 Morocco +212' => '212',
            '🇶🇦 Qatar +974' => '974',
            '🇷🇺 Russia +7' => '7',
            '🇸🇦 Saudi Arabia +966' => '966',
            '🇪🇸 Spain +34' => '34',
            '🇹🇳 Tunisia +216' => '216',
            '🇬🇧 UK +44' => '44',
            '🇦🇪 United Arab Emirates +971' => '971',
            '🇺🇸 USA +1' => '1',
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z][a-zA-Z0-9]*$/',
                        'message' => 'Pseudo must start with a letter and contain only alphanumeric characters.'
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'First Name',
                'constraints' => [
                    
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'First name must contain only letters.'
                    ]),
                    new Assert\Length(['max' => 20]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Last Name',
                'constraints' => [
                    
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'Last name must contain only letters.'
                    ]),
                    new Assert\Length(['max' => 20]),
                ],

            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                  
                    new Assert\Email(),
                ],
            ],)
            ->add('motDePasseHash', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'mapped' => false,
            ])
            ->add('countryCode', ChoiceType::class, [
                'choices' => $this->getCountryChoices(),
                'label' => false,
                'attr' => ['class' => 'selectpicker', 'style' => 'margin-right: 5px;'],
                'mapped' => false,
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Phone number', 'style' => 'width: 300px; margin-left: 5px;'],
                'required' => true,
            ])
            ->add('gender', ChoiceType::class, [

                'required' => false,
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                    'Other' => 'other',
                ],
                'placeholder' => 'Select Gender',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                
            ])
            ->add('resetCode', TextType::class, ['label' => 'Code'])
            ->add('register', SubmitType::class, ['label' => 'Register']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
