<?php

namespace App\Tests\Entity;

use App\Entity\Level;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LevelTest extends KernelTestCase
{
    public function getEntity(): Level
    {
        $level = (new Level())
            ->setName('TestLevel');

        return $level;
    }

    public function assertHasErrors(Level $level, int $nbErrors = 0)
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($level);

        $this->assertCount($nbErrors, $error);
    }

    public function testCreateLevel()
    {
        $level = $this->getEntity();

        $this->assertInstanceOf(Level::class, $level);
        $this->assertSame('TestLevel', $level->getName());
    }

    public function testUpdateLevel()
    {
        $level = $this->getEntity();

        $level->setName('UpdatedLevel');

        $this->assertSame('UpdatedLevel', $level->getName());
    }

    public function testValidLevel()
    {
        $this->assertHasErrors($this->getEntity(), 0);
        $this->assertHasErrors($this->getEntity()->setName('LevelTesté'), 0);
    }

    public function testInvalidName()
    {
        $this->assertHasErrors($this->getEntity()->setName('123'), 1);
        $this->assertHasErrors($this->getEntity()->setName('Test1'), 1);
    }

    public function testInvalidBlankName()
    {
        $this->assertHasErrors($this->getEntity()->setName(''), 1);
    }
}
