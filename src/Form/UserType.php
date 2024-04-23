<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    private $assetMapper;

    public function __construct(AssetMapperInterface $assetMapper)
    {
        $this->assetMapper = $assetMapper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        dump($this->assetMapper->getPublicPath('images/avatars/woman1.jpg'));
        $builder
            ->add('avatar', ChoiceType::class, [
                'label' => 'Votre avatar',
                'required' => true,
                'choices' => [
                    'Femme 1' => $this->assetMapper->getPublicPath('images/avatars/woman1.jpg'),
                    'Femme 2' => $this->assetMapper->getPublicPath('images/avatars/woman2.jpg'),
                    'Femme 3' => $this->assetMapper->getPublicPath('images/avatars/woman3.jpg'),
                    'Femme 4' => $this->assetMapper->getPublicPath('images/avatars/woman4.jpg'),
                    'Homme 1' => $this->assetMapper->getPublicPath('images/avatars/man1.jpg'),
                    'Homme 2' => $this->assetMapper->getPublicPath('images/avatars/man2.jpg'),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'required' => false
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'required' => false
            ])
            ->add('username', TextType::class, [
                'label' => 'Votre nom d\'utilisateur',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
