<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/administration/document', name: "admin_document_")]
class DocumentCrudController extends AbstractController
{
    #[Route('/nouveau-document', name: 'create')]
    #[Route('/editer-document/{id}', name: 'update')]
    public function handle(Document $document = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        $edit = true;
        if (!$document) {
            $document = new Document();
            $edit = false;
        }
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($document);
            $entityManager->flush();
            return $this->redirectToRoute('admin_document_read');
        }
        return $this->render('admin/document/handle.html.twig', [
            'form' => $form->createView(),
            'edit' => $edit,
        ]);
    }

    #[Route('/', name: 'read')]
    public function read(DocumentRepository $documentRepository): Response
    {
        $documents = $documentRepository->findAll();
        return $this->render('admin/document/read.html.twig', [
            'documents' => $documents,
        ]);
    }

    #[Route('/supprimer-document/{id}', name: 'delete')]
    public function delete(Document $document, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($document);
        $entityManager->flush();
        return $this->redirectToRoute('admin_document_read');
    }
}
