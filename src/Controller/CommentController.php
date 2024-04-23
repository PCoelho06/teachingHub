<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Comment;
use App\Entity\Document;
use App\Form\CommentType;
use App\Services\Rating;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commenter', name: 'comment_')]
class CommentController extends AbstractController
{
    #[Route('/{slug}', name: 'add')]
    #[Route('/editer-un-commentaire/{id}', name: 'edit')]
    public function handleComment(Request $request, #[MapEntity(mapping: ['slug' => 'slug'])] Document $document = null, EntityManagerInterface $entityManager, #[MapEntity(mapping: ['id' => 'id'])] Comment $comment = null): Response
    {
        if (is_null($comment)) {
            $comment = new Comment();
            $comment->setDocument($document);
        } else {
            dump($comment);
            if (!$this->getUser()) {
                $this->addFlash(
                    'danger',
                    'Attention, vous devez vous connecter pour modifier ce commentaire.'
                );
                return $this->redirect('app_login');
            } else if ($this->getUser() != $comment->getAuthor()) {
                $this->addFlash(
                    'danger',
                    'Attention, vous n\'avez pas les droits pour modifier ce commentaire'
                );
                return $this->redirectToRoute('document_show', [
                    'slug' => $comment->getDocument()->getSlug(),
                ]);
            }
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            if (!is_null($comment->getId())) {
                $comment->setEditedAt(new DateTimeImmutable());
            } else {
                $comment->setCreatedAt(new DateTimeImmutable())
                    ->setAuthor($this->getUser());
            }

            $entityManager->persist($comment);

            $document = $comment->getDocument();
            if (!is_null($comment->getId())) {
                $document->setRatingAverage(Rating::calculateRating($document));
            } else {
                $document->setRatingAverage(Rating::calculateRating($document, $comment->getRating()));
            }
            $entityManager->persist($document);

            $entityManager->flush();

            return $this->redirectToRoute('document_show', ['slug' => $comment->getDocument()->getSlug()]);
        }

        return $this->render('comment/handle.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-un-commentaire/{id}', name: 'delete')]
    public function delete(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() != $comment->getAuthor()) {
            $this->addFlash(
                'danger',
                'Attention, vous n\'avez pas les droits pour supprimer ce commentaire'
            );
            return $this->redirectToRoute('document_show', [
                'slug' => $comment->getDocument()->getSlug(),
            ]);
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('user_documents_comments');
    }
}
