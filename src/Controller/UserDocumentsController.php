<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Document;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-compte/mes-documents', name: 'user_documents_')]
class UserDocumentsController extends AbstractController
{
    #[Route('', name: 'uploads')]
    public function showUserUploadedDocuments(#[CurrentUser] User $user): Response
    {
        $documents = $user->getDocuments();
        return $this->render('user_documents/show.html.twig', [
            'page' => 'déposés',
            'documents' => $documents,
        ]);
    }

    #[Route('-favoris', name: 'favorites')]
    public function showUserFavoriteDocuments(#[CurrentUser] User $user): Response
    {
        $documents = $user->getFavoriteDocuments();
        return $this->render('user_documents/show.html.twig', [
            'page' => 'favoris',
            'documents' => $documents,
        ]);
    }

    #[Route('-telecharges', name: 'downloads')]
    public function showUserDownloadedDocuments(#[CurrentUser] User $user): Response
    {
        $documents = $user->getDownloadedDocuments();
        return $this->render('user_documents/show.html.twig', [
            'page' => 'téléchargés',
            'documents' => $documents,
        ]);
    }

    #[Route('/ajouter-favoris/{id}', name: 'add_favorite')]
    public function addUserFavoriteDocuments(#[CurrentUser] User $user, Request $request, Document $document, EntityManagerInterface $entityManagerInterface): Response
    {
        $document->addFavoriteUser($user);

        $entityManagerInterface->persist($document);
        $entityManagerInterface->flush();

        if ($request->query->get('origin') == 'document_search') {
            return $this->redirectToRoute($request->query->get('origin'));
        }

        return $this->redirectToRoute('document_show', [
            'slug' => $document->getSlug()
        ]);
    }

    #[Route('/retirer-favoris/{id}', name: 'remove_favorite')]
    public function removeUserFavoriteDocuments(#[CurrentUser] User $user, Request $request, Document $document, EntityManagerInterface $entityManagerInterface): Response
    {
        $document->removeFavoriteUser($user);

        $entityManagerInterface->persist($document);
        $entityManagerInterface->flush();

        if ($request->query->get('origin') == 'document_search') {
            return $this->redirectToRoute($request->query->get('origin'));
        }

        return $this->redirectToRoute('document_show', [
            'slug' => $document->getSlug()
        ]);
    }
}
