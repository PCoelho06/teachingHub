<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => 'name',
                'required' => false,
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'name',
                'required' => false,
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'name',
                'required' => false,
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez ici les termes de votre recherche',
                ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher un document',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
