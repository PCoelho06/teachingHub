<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Document;
use App\Form\DocumentSearchType;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/documents', name: 'document_')]
class DocumentController extends AbstractController
{
    #[Route('/chercher-un-document', name: 'search')]
    public function search(Request $request, DocumentRepository $documentRepository): Response
    {
        $form = $this->createForm(DocumentSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $criteria = $form->getData();
            $documents = $documentRepository->findBySearchCriteria($criteria);

            return $this->render('document/results.html.twig', [
                'documents' => $documents,
            ]);
        }

        return $this->render('document/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deposer-un-document', name: 'add')]
    #[Route('/editer-un-document/{slug}', name: 'update')]
    public function handleDocument(Document $document = null, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if (is_null($document)) {
            $document = new Document();
            $edit = false;
        } else {
            $edit = true;
            if (!$this->getUser()) {
                $this->addFlash(
                    'danger',
                    'Attention, vous devez vous connecter pour modifier ce document.'
                );
                return $this->redirect('app_login');
            } else if ($this->getUser() != $document->getAuthor()) {
                $this->addFlash(
                    'danger',
                    'Attention, vous n\'avez pas les droits pour modifier ce document'
                );
                return $this->redirect('document_show', [
                    'slug' => $document->getSlug(),
                ]);
            }
        }

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            $slug = $slugger->slug(uniqid() . '.' . $document->getTitle());
            $filename = $slug . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('documents_directory'),
                    $filename
                );
            } catch (FileException $e) {
                $this->addFlash(
                    'danger',
                    'Votre document n\'a pas pu être téléchargé. Si le problème persiste, merci de nous contacter.'
                );

                if (is_null($document->getId())) {
                    return $this->redirect('document_add');
                } else {
                    return $this->redirect('document_update', [
                        'slug' => $document->getSlug(),
                    ]);
                }
            }

            if (!is_null($document->getId())) {
                $document->setUpdatedAt(new DateTimeImmutable());
            } else {
                $document->setUploadedAt(new DateTimeImmutable());
            }

            $document->setSlug($slug)
                ->setFile($filename)
                ->setAuthor($this->getUser());

            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('document_show', ['slug' => $slug]);
        }

        return $this->render('document/handle.html.twig', [
            'form' => $form,
            'edit' => $edit,
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function showDocument(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/supprimer-un-document/{slug}', name: 'delete')]
    public function delete(Document $document, EntityManagerInterface $entityManager): Response
    {
        // supprimer le document du dossier upload


        $entityManager->remove($document);
        $entityManager->flush();

        return $this->redirectToRoute('user_documents_show');
    }
}
