<?php

namespace App\DataFixtures;

use App\Entity\Comment;
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

        $levels = [
            [
                'name' => 'Sixième',
                'short' => '6eme',
                'color' => 'sixieme'
            ],
            [
                'name' => 'Cinquième',
                'short' => '5eme',
                'color' => 'cinquieme'
            ],
            [
                'name' => 'Quatrième',
                'short' => '4eme',
                'color' => 'quatrieme'
            ],
            [
                'name' => 'Troisième',
                'short' => '3eme',
                'color' => 'troisieme'
            ],
            [
                'name' => 'Seconde',
                'short' => '2nde',
                'color' => 'seconde'
            ],
            [
                'name' => 'Première',
                'short' => '1ere',
                'color' => 'premiere'
            ],
            [
                'name' => 'Terminale',
                'short' => 'Tale',
                'color' => 'terminale'
            ],
        ];

        for ($i = 0; $i < 8; $i++) {
            $type = new Type();
            $type->setName(ucfirst($faker->word()));
            $this->addReference('type_' . $i, $type);
            $manager->persist($type);
        }

        foreach ($levels as $key => $newLevel) {
            $level = new Level();
            $level->setName($newLevel['name'])
                ->setShort($newLevel['short'])
                ->setColor($newLevel['color']);
            $this->addReference('level_' . $key, $level);
            $manager->persist($level);
        }

        for ($i = 0; $i < 15; $i++) {
            $subject = new Subject();
            $subject->setName(ucfirst($faker->word()));
            for ($j = 0; $j < 6; $j++) {
                $level = $this->getReference('level_' . $j);
                $subject->addLevel($level);
            }
            $this->addReference('subject_' . $i, $subject);
            $manager->persist($subject);
        }

        for ($j = 0; $j < 225; $j++) {
            $theme = new Theme();
            $theme->setName(ucfirst($faker->word()));
            for ($i = 0; $i < $faker->numberBetween(1, 3); $i++) {
                $theme->addSubject($this->getReference('subject_' . $faker->numberBetween(0, 14)));
            }
            for ($i = 0; $i < $faker->numberBetween(1, 2); $i++) {
                $theme->addLevel($this->getReference('level_' . $faker->numberBetween(0, 6)));
            }
            $this->addReference('theme_' . $j, $theme);
            $manager->persist($theme);
        }

        for ($i = 0; $i < 60; $i++) {
            $slugger = new AsciiSlugger();
            $document = new Document();

            $type = $this->getReference('type_' . $faker->numberBetween(0, 5));
            $theme = $this->getReference('theme_' . $faker->numberBetween(0, 224));
            $subject = $theme->getSubjects()[0];
            $level = $theme->getLevels()[0];

            $document->setTitle($faker->words(3, true))
                ->setDescription($faker->paragraph())
                // ->setFile($faker->file('/Users/pierre/Workspace/teachingHub-test-documents', './public/uploads/documents', false))
                ->setRatingAverage($faker->randomFloat(1, 0, 5))
                ->setUploadedAt(new DateTimeImmutable())
                ->setDownloadsNumber(0)
                ->setAuthor($this->getReference('user_' . $faker->numberBetween(0, 14)));
            $slug = $slugger->slug($document->getTitle());
            $document->setSlug($slug);
            copy('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf', './public/uploads/documents/' . $document->getSlug() . '.pdf');
            $document->setFile($document->getSlug() . '.pdf');
            $document->setType($type)
                ->addLevel($level)
                ->addSubject($subject)
                ->addTheme($theme);

            $this->addReference('document_' . $i, $document);
            $manager->persist($document);
        }

        for ($i = 0; $i < 45; $i++) {
            $comment = new Comment();

            $comment->setCreatedAt(new DateTimeImmutable())
                ->setContent($faker->paragraphs(2, true))
                ->setRating($faker->numberBetween(1, 5))
                ->setAuthor($this->getReference('user_' . $faker->numberBetween(0, 14)))
                ->setDocument($this->getReference('document_' . $faker->numberBetween(0, 59)));

            $manager->persist($comment);
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
