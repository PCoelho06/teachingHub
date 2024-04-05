<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use App\Entity\Subject;
use App\Data\SearchFilters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EntityType::class, [
                'label' => 'Type de document',
                'class' => Type::class,
                'choice_label' => 'name',
                'required' => false,
                // 'multiple' => true,
                // 'expanded' => true,
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ])
            ->add('level', EntityType::class, [
                'label' => 'Niveau',
                'class' => Level::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ])
            ->add('subject', EntityType::class, [
                'label' => 'Matière',
                'class' => Subject::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ])
            ->add('theme', EntityType::class, [
                'label' => 'Thématique',
                'class' => Theme::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' =>  'Entrez ici les termes de votre recherche'
                ],
                'required' => false,
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ])
            ->add('orderBy', ChoiceType::class, [
                'choices' => [
                    'Date de publication' => 'uploadedAt',
                    'Note Moyenne' => 'ratingAverage',
                    'Nombre de Téléchargements' => 'downloadsNumber',
                    'Ordre Alphabétique' => 'title',
                ],
                'empty_data' => 'uploadedAt',
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchFilters::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
