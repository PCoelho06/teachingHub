<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use App\Entity\Subject;
use App\Data\SearchFilters;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\TranslatableMessage;

class DocumentSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formThemeModifier = function (FormInterface $form, Subject $subject = null): void {
            $themes = null === $subject ? [] : $subject->getThemes();

            $form->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choices' => $themes,
                'label' => 'Thématique',
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'onchange' => "this.closest('form').submit()"
                ]
            ]);
        };

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

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formThemeModifier): void {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                $formThemeModifier($event->getForm(), $data->getSubject());
            }
        );

        $builder->get('subject')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formThemeModifier): void {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $subject = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback function!
                $formThemeModifier($event->getForm()->getParent(), $subject);
            }
        );
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
