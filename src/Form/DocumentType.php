<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use App\Entity\Subject;
use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfonycasts\DynamicForms\DependentField;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

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
                // 'required' => true,
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
                // 'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('subjects', EntityType::class, [
                'label' => 'Matière',
                'class' => Subject::class,
                'choice_label' => 'name',
                // 'required' => true,
                'multiple' => true,
                'expanded' => true,
            ]);
        // ->add('themes', EntityType::class, [
        //     'label' => 'Thématique',
        //     'class' => Theme::class,
        //     'choice_label' => 'name',
        //     'required' => true,
        //     'multiple' => true,
        //     'expanded' => true,
        // ]);

        $builder->addDependent('themes', ['levels', 'subjects'], function (DependentField $field, ArrayCollection $levels, ArrayCollection $subjects) {
            if ($levels->isEmpty() || $subjects->isEmpty()) {
                return;
            }

            $levelsThemes = [];
            $subjectsThemes = [];

            foreach ($levels as $level) {
                $levelsThemes = array_merge($levelsThemes, $level->getThemes()->toArray());
            }
            dump($levelsThemes);

            foreach ($subjects as $subject) {
                $subjectsThemes = array_merge($subjectsThemes, $subject->getThemes()->toArray());
            }
            dump($subjectsThemes);

            $themes = array_intersect($levelsThemes, $subjectsThemes);
            dump($themes);

            $field->add(ChoiceType::class, [
                'label' => 'Thématique',
                'choices' => $themes,
                'choice_label' => 'name',
                'required' => true,
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
