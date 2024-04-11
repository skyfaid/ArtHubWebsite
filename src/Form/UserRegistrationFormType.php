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
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [ 
            ])
            ->add('prenom', TextType::class, [ 
                'label' => 'First Name',
            ])
            ->add('nom', TextType::class, [ 
                'label' => 'Last Name',
            ])
            ->add('email', EmailType::class, [  
            ])
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
                'required' => true,
                'error_bubbling' => true
            ])
            ->add('phoneNumber', TelType::class, [ 
                'label' => false,
                'attr' => ['placeholder' => 'Phone number', 'style' => 'width: 300px; margin-left: 5px;'],
                'required' => true, 
                
            ])
            ->add('gender', ChoiceType::class, [ 'error_bubbling' => true,
                'required' => true,
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                    'Other' => 'other',
                    
                ],
                'placeholder' => 'Select Gender',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'error_bubbling' => true
                
            ])
            ->add('register', SubmitType::class, ['label' => 'Register']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }

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
}
