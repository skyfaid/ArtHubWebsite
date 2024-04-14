<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Utilisateurs;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'attr' => ['class' => 'form-control','style' => 'width: 80%;'],
                'label' => 'Username', 
            ])

            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control','style' => 'width: 80%;'],
                'label' => 'First name',    
            ])

            ->add('nom', TextType::class, [
                'label' => 'Last name',
                'attr' => ['class' => 'form-control','style' => 'width: 80%;'],
            ])

            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control','style' => 'width: 80%;'],  
            ])

            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-sm ms-auto'],
                'label' => 'Save Changes'
            ])
            ->add('saveinfo', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-sm ms-auto'],
                'label' => 'Save '
            ])
            ->add('urlImageProfil', FileType::class, [
                'label' => 'Profile Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('countryCode', ChoiceType::class, [
                'choices' => [
                    '🇩🇿 Algeria +213' => '213',
                    '🇪🇬 Egypt +20' => '20',
                    '🇫🇷 France +33' => '33',
                    '🇩🇪 Germany +49' => '49',
                    '🇬🇷 Greece +30' => '30',
                    '🇮🇹 Italy +39' => '39',
                    '🇱🇧 Lebanon +961' => '961',
                    '🇱🇾 Libya +218' => '218',
                    '🇲🇦 Morocco +212' => '212',
                    '🇪🇸 Spain +34' => '34',
                    '🇹🇳 Tunisia +216' => '216',
                    '🇬🇧 UK +44' => '44',
                    '🇦🇪 United Arab Emirates +971' => '971',
                    '🇺🇸 USA +1' => '1'
                ],
                'attr' => ['class' => 'form-control','style' => 'width: 30%;', ],
               
                'label' => false,
                'mapped' => false,
            ])
            ->add('phoneNumber', TextType::class, [
                'attr' => ['class' => 'form-control','style' => 'width: 100%; margin-right: 20px;'],
                'label' => false,
                
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Other' => 'Other'
                ],
                'attr' => ['class' => 'form-control','style' => 'width: 80%;']
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password',
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                
            ])
        
        ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'New Password'],
                'second_options' => ['label' => 'Confirm New Password'],
                'mapped' => false,
                
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
