<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-compte/mes-documents', name: 'user_documents_')]
class UserDocumentsController extends AbstractController
{
    #[Route('', name: 'show')]
    public function showUserDocuments(DocumentRepository $documentRepository, #[CurrentUser] User $user): Response
    {
        $documents = $documentRepository->findByAuthor($user);
        return $this->render('user_documents/show.html.twig', [
            'documents' => $documents,
        ]);
    }
}
