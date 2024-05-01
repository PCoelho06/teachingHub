<?php

namespace App\Controller;

use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/administration', name: "admin_")]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(DocumentRepository $documentRepository, UserRepository $userRepository): Response
    {
        $nbDocuments = $documentRepository->count();
        $nbDownloads = $documentRepository->countDownloadNumber()['nbDownloads'];
        $nbUsers = $userRepository->count();
        return $this->render('admin/index.html.twig', [
            'nbDocuments' => $nbDocuments,
            'nbDownloads' => $nbDownloads,
            'nbUsers' => $nbUsers,
        ]);
    }
}
