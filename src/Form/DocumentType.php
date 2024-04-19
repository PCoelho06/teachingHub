<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use App\Entity\Subject;
use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Collections\Collection;
use Symfonycasts\DynamicForms\DependentField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'row_attr' => ['onchange' => 'this.form.submit()'],
            ])
            ->add('subjects', EntityType::class, [
                'label' => 'Matière',
                'class' => Subject::class,
                'choice_label' => 'name',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'row_attr' => ['onchange' => 'this.form.submit()'],
            ]);
        $builder->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => ['class' => 'btn btn-primary'],
        ]);

        $builder->addDependent('themes', ['levels', 'subjects'], function (DependentField $field, ?Collection $levels, ?Collection $subjects) {
            if (count($levels) === 0 || count($subjects) === 0) {
                return;
            }

            $levelThemes = [];
            foreach ($levels as $l) {
                $levelThemes = array_merge($levelThemes, $l->getThemes()->toArray());
            }
            $subjectThemes = [];
            foreach ($subjects as $s) {
                $subjectThemes = array_merge($subjectThemes, $s->getThemes()->toArray());
            }
            $themes = array_intersect($levelThemes, $subjectThemes);

            $field->add(EntityType::class, [
                'label' => 'Thématique',
                'class' => Theme::class,
                'choices' => $themes,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
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
