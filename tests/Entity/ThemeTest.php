<?php

namespace App\Tests\Entity;

use App\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ThemeTest extends KernelTestCase
{
    public function getEntity(): Theme
    {
        $theme = (new Theme())
            ->setName('TestTheme');

        return $theme;
    }

    public function assertHasErrors(Theme $theme, int $nbErrors = 0)
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($theme);

        $this->assertCount($nbErrors, $error);
    }

    public function testCreateTheme()
    {
        $theme = $this->getEntity();

        $this->assertInstanceOf(Theme::class, $theme);
        $this->assertSame('TestTheme', $theme->getName());
    }

    public function testUpdateTheme()
    {
        $theme = $this->getEntity();

        $theme->setName('UpdatedTheme');

        $this->assertSame('UpdatedTheme', $theme->getName());
    }

    public function testValidTheme()
    {
        $this->assertHasErrors($this->getEntity(), 0);
        $this->assertHasErrors($this->getEntity()->setName('ThemeTesté'), 0);
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
