<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;
    private $assetMapper;

    public function __construct(UserPasswordHasherInterface $hasher, AssetMapperInterface $assetMapper)
    {
        $this->hasher = $hasher;
        $this->assetMapper = $assetMapper;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $avatar = $this->assetMapper->getPublicPath('images/avatars/man1.jpg');

        $user = (new User())
            ->setEmail('p.coelho@lapinou.tech')
            ->setFirstname('Pierre')
            ->setLastname('Coelho')
            ->setUsername('p.coelho')
            ->setRegisteredAt(new DateTimeImmutable())
            ->setAvatar($avatar)
            ->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->hasher->hashPassword(
            $user,
            'test'
        );

        $user->setPassword($hashedPassword);
        $this->addReference('user_0', $user);
        $manager->persist($user);

        for ($i = 1; $i < 15; $i++) {
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $username = strtolower($firstname) . '.' . strtolower($lastname);

            $user = (new User())
                ->setEmail($username . '@ac-nice.fr')
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setUsername($username)
                ->setRegisteredAt(new DateTimeImmutable())
                ->setAvatar($avatar);

            $hashedPassword = $this->hasher->hashPassword(
                $user,
                'test'
            );

            $user->setPassword($hashedPassword);
            $this->addReference('user_' . $i, $user);

            $manager->persist($user);
        }


        $manager->flush();
    }
}
