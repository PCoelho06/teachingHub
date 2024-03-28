<?php

namespace App\Tests\Entity;

use App\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SubjectTest extends KernelTestCase
{
    public function getEntity(): Subject
    {
        $subject = (new Subject())
            ->setName('TestSubject');

        return $subject;
    }

    public function assertHasErrors(Subject $subject, int $nbErrors = 0)
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($subject);

        $this->assertCount($nbErrors, $error);
    }

    public function testCreateSubject()
    {
        $subject = $this->getEntity();

        $this->assertInstanceOf(Subject::class, $subject);
        $this->assertSame('TestSubject', $subject->getName());
    }

    public function testUpdateSubject()
    {
        $subject = $this->getEntity();

        $subject->setName('UpdatedSubject');

        $this->assertSame('UpdatedSubject', $subject->getName());
    }

    public function testValidSubject()
    {
        $this->assertHasErrors($this->getEntity(), 0);
        $this->assertHasErrors($this->getEntity()->setName('SubjectTesté'), 0);
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
