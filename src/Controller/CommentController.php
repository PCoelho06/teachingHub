<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Document;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commenter', name: 'comment_')]
class CommentController extends AbstractController
{
    #[Route('/{slug}', name: 'add')]
    public function add(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setDocument($document);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable())
                ->setAuthor($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render('comment/add.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }
}
