<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $passwordHasher = new UserPasswordHasherInterface();
        $faker = Factory::create('fr_FR');
        $username = $faker->userName();

        $user = (new User())
            ->setEmail($username . '@ac-nice.fr')
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setUsername($username);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            'test'
        );

        $user->setPassword($hashedPassword);

        $manager->persist($user);

        $manager->flush();
    }
}
