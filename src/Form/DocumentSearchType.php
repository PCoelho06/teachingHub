<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use App\Entity\Subject;
use App\Data\SearchFilters;
use App\Repository\SubjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfonycasts\DynamicForms\DependentField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class DocumentSearchType extends AbstractType
{
    private SubjectRepository $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('type', EntityType::class, [
                'label' => 'Type de document',
                'class' => Type::class,
                'choice_label' => 'name',
                'required' => false,
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
            ->add('orderBy', ChoiceType::class, [
                'choices' => [
                    'Date de publication' => 'uploadedAt',
                    'Note Moyenne' => 'ratingAverage',
                    'Nombre de Téléchargements' => 'downloadsNumber',
                    'Ordre Alphabétique' => 'title',
                ],
                'label' => 'Trier par',
                'empty_data' => 'uploadedAt',
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ])
            ->add('title', SearchType::class, [
                'attr' => [
                    'placeholder' =>  'Entrez ici les termes de votre recherche'
                ],
                'required' => false,
            ])
            ->add('ratingAverage', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'expanded' => true,
                'label' => 'Note minimale',
                'required' => false,
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ]);

        $builder->addDependent('theme', ['level', 'subject'], function (DependentField $field, ?Level $level, ?Subject $subject) {
            (!empty($level) && !empty($subject)) ?
                $themes = array_intersect($level->getThemes()->toArray(), $subject->getThemes()->toArray())
                : $themes = [];

            $field->add(EntityType::class, [
                'label' => 'Thématique',
                'class' => Theme::class,
                'choices' => $themes,
                'choice_label' => 'name',
                'disabled' => empty($level) || empty($subject),
                'required' => false,
            ]);
        });

        $builder->addDependent('subject', ['level'], function (DependentField $field, ?Level $level) {
            $field->add(EntityType::class, [
                'label' => 'Matière',
                'class' => Subject::class,
                'choices' => $level ? $level->getSubjects() : $this->subjectRepository->findAll(),
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchFilters::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
