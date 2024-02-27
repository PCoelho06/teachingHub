<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use App\Entity\Subject;
use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du document'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du document'
            ])
            ->add('file', FileType::class, [
                'label' => 'Document',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Merci de selectionner un document PDF.',
                    ])
                ],
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type de document',
                'class' => Type::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('levels', EntityType::class, [
                'label' => 'Niveau',
                'class' => Level::class,
                'choice_label' => 'name',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('subjects', EntityType::class, [
                'label' => 'Matière',
                'class' => Subject::class,
                'choice_label' => 'name',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('themes', EntityType::class, [
                'label' => 'Thématique',
                'class' => Theme::class,
                'choice_label' => 'name',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
