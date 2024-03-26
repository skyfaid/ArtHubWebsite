<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSignInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Email'],
                'label' => false,
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Password'],
                'label' => false,
            ])
            
            ->add('sign_in', SubmitType::class, [
                'attr' => ['class' => 'btn btn-lg btn-primary bg-gradient-dark btn-lg w-100 mt-4 mb-0'],
                'label' => 'Sign in',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
