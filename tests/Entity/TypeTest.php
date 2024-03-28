<?php

namespace App\Tests\Entity;

use App\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TypeTest extends KernelTestCase
{
    public function getEntity(): Type
    {
        $type = (new Type())
            ->setName('TestType');

        return $type;
    }

    public function assertHasErrors(Type $type, int $nbErrors = 0)
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($type);

        $this->assertCount($nbErrors, $error);
    }

    public function testCreateType()
    {
        $type = $this->getEntity();

        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame('TestType', $type->getName());
    }

    public function testUpdateType()
    {
        $type = $this->getEntity();

        $type->setName('UpdatedType');

        $this->assertSame('UpdatedType', $type->getName());
    }

    public function testValidType()
    {
        $this->assertHasErrors($this->getEntity(), 0);
        $this->assertHasErrors($this->getEntity()->setName('TypeTesté'), 0);
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
