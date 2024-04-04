<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'attr' => [ 'class' => 'form-control'],
            'label' => 'Email Address',
            'required' => true,
        ])
        ->add('password', PasswordType::class, [
            'attr' => [ 'class' => 'form-control'],
            'label' => 'Password',
            'required' => true,
        ])
        // The Submit button can be added here or directly in the template if you prefer
        ->add('login', SubmitType::class, [
            'attr' => ['class' => 'thm-btn donate-one__btn'],
            'label' => 'Login',
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
