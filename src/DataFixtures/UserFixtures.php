<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $username = $faker->userName();

        $user = (new User())
            ->setEmail($username . '@ac-nice.fr')
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setUsername($username)
            ->setRegisteredAt(new DateTimeImmutable());

        $hashedPassword = $this->hasher->hashPassword(
            $user,
            'test'
        );

        $user->setPassword($hashedPassword);
        $this->addReference('user', $user);

        $manager->persist($user);

        $manager->flush();
    }
}
