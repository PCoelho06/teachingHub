<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentTest extends KernelTestCase
{
    public function getEntity(): Comment
    {
        $comment = (new Comment())
            ->setRating(4)
            ->setContent('Test Content')
            ->setCreatedAt(new \DateTimeImmutable());

        return $comment;
    }

    public function assertHasErrors(Comment $comment, int $nbErrors = 0)
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($comment);

        $this->assertCount($nbErrors, $error);
    }

    public function testCreateComment()
    {
        $comment = $this->getEntity();

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertSame(4, $comment->getRating());
        $this->assertSame('Test Content', $comment->getContent());
        $this->assertInstanceOf(\DateTimeImmutable::class, $comment->getCreatedAt());
    }

    public function testUpdateComment()
    {
        $comment = $this->getEntity();

        $comment->setRating(5);
        $comment->setContent('Updated Content');
        $comment->setEditedAt(new \DateTimeImmutable());

        $this->assertSame(5, $comment->getRating());
        $this->assertSame('Updated Content', $comment->getContent());
        $this->assertInstanceOf(\DateTimeImmutable::class, $comment->getEditedAt());
    }

    public function testValidComment()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankContent()
    {
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }

    public function testInvalidBlankRating()
    {
        $this->assertHasErrors($this->getEntity()->setRating(0), 1);
        $this->assertHasErrors($this->getEntity()->setRating(6), 1);
    }
}
