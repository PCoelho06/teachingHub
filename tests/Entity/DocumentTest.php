<?php

namespace App\Tests\Entity;

use App\Entity\Document;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DocumentTest extends KernelTestCase
{
    public function getEntity(): Document
    {
        $document = (new Document())
            ->setTitle('TestDocument')
            ->setDescription('Test Description')
            ->setUploadedAt(new \DateTimeImmutable());

        return $document;
    }

    public function assertHasErrors(Document $document, int $nbErrors = 0)
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($document);

        $this->assertCount($nbErrors, $error);
    }

    public function testCreateDocument()
    {
        $document = $this->getEntity();

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame('TestDocument', $document->getTitle());
        $this->assertSame('Test Description', $document->getDescription());
        $this->assertInstanceOf(DateTimeImmutable::class, $document->getUploadedAt());
    }

    public function testUpdateDocument()
    {
        $document = $this->getEntity();

        $document->setTitle('UpdatedDocument');
        $document->setDescription('Updated Description');
        $document->setUpdatedAt(new \DateTimeImmutable());

        $this->assertSame('UpdatedDocument', $document->getTitle());
        $this->assertSame('Updated Description', $document->getTitle());
        $this->assertInstanceOf(DateTimeImmutable::class, $document->getUpdatedAt());
    }

    public function testValidDocument()
    {
        $this->assertHasErrors($this->getEntity(), 0);
        $this->assertHasErrors($this->getEntity()->setTitle('DocumentTesté'), 0);
    }

    public function testInvalidTitle()
    {
        $this->assertHasErrors($this->getEntity()->setTitle('123'), 1);
        $this->assertHasErrors($this->getEntity()->setTitle('Test1'), 1);
    }

    public function testInvalidBlankTitle()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testInvalidBlankDescription()
    {
        $this->assertHasErrors($this->getEntity()->setDescription(''), 1);
    }
}
