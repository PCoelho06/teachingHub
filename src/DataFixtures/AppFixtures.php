<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use DateTimeImmutable;
use App\Entity\Subject;
use App\Entity\Document;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 6; $i++) {
            $type = new Type();
            $type->setName(ucfirst($faker->word()));
            $this->addReference('type_' . $i, $type);
            $manager->persist($type);
        }

        for ($i = 0; $i < 10; $i++) {
            $level = new Level();
            $level->setName(ucfirst($faker->word()));
            $this->addReference('level_' . $i, $level);
            $manager->persist($level);
        }

        for ($i = 0; $i < 15; $i++) {
            $subject = new Subject();
            $subject->setName(ucfirst($faker->word()));
            for ($j = 0; $j < $faker->numberBetween(1, 10); $j++) {
                $level = $this->getReference('level_' . $j);
                $subject->addLevel($level);
            }
            for ($j = 0; $j < 15; $j++) {
                $theme = new Theme();
                $theme->setName(ucfirst($faker->word()))
                    ->addSubject($subject);
                $this->addReference('theme_' . $j + $i * 15, $theme);
                $manager->persist($theme);
            }
            $manager->persist($subject);
        }

        for ($i = 0; $i < 9; $i++) {
            $slugger = new AsciiSlugger();
            $document = new Document();

            $type = $this->getReference('type_' . $faker->numberBetween(0, 5));
            $theme = $this->getReference('theme_' . $faker->numberBetween(0, 224));
            $subjects = $theme->getSubjects();
            $subject = $subjects[0];
            $levels = $subject->getLevels();
            $level = $levels[$faker->numberBetween(0, count($levels) - 1)];

            $document->setTitle($faker->words(3, true))
                ->setDescription($faker->paragraph())
                ->setFile($faker->file('C:\Users\DWWM\Documents\pdf', 'C:\laragon\www\teachingHub\public\uploads\documents', false))
                ->setRatingAverage($faker->randomFloat(1, 0, 5))
                ->setUploadedAt(new DateTimeImmutable())
                ->setDownloadsNumber(0)
                ->setAuthor($this->getReference('user'));
            $slug = $slugger->slug($document->getTitle());
            $document->setSlug($slug);
            $document->setType($type)
                ->addLevel($level)
                ->addSubject($subject)
                ->addTheme($theme);

            $manager->persist($document);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
