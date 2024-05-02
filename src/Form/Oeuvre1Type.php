<?php

namespace App\Form;

use App\Entity\Oeuvre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Oeuvre1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       // In your OeuvreType form class
->add('titre', TextType::class, [
    'constraints' => [
        new NotBlank([
            'message' => 'Le titre ne peut pas être vide.',
        ]),
    ],
])

            ->add('description')
            ->add('disponibilite')
            ->add('prix', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, // ajustez selon vos besoins
                
            ])
            ->add('datecreation', DateType::class, [
                'widget' => 'single_text',
                // Ajoutez l'option HTML5 pour utiliser le picker de date natif du navigateur
                'html5' => true,
                // Vous pouvez aussi définir un format d'affichage personnalisé, si nécessaire
                'format' => 'yyyy-MM-dd',
            ])
            // ...
        
            ->add('type')
            ->add('posterurl', FileType::class, [
                'label' => 'Poster (Image file)',
                'mapped' => false, // Ne mappez pas directement à une propriété de l'entité
                'required' => false,
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvre::class,
        ]);
    }
}
