<?php

namespace App\Controller\Admin;

use App\Entity\Level;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/administration/niveaux', name: "admin_level_")]
class LevelCrudController extends AbstractController
{
    #[Route('/nouveau-niveau', name: 'create')]
    #[Route('/editer-niveau/{id}', name: 'update')]
    public function handle(Level $level = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        $edit = true;
        if (!$level) {
            $level = new Level();
            $edit = false;
        }
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($level);
            $entityManager->flush();
            return $this->redirectToRoute('admin_level_read');
        }
        return $this->render('admin/level/handle.html.twig', [
            'form' => $form->createView(),
            'edit' => $edit,
        ]);
    }

    #[Route('/', name: 'read')]
    public function read(LevelRepository $levelRepository): Response
    {
        $levels = $levelRepository->findAll();
        return $this->render('admin/level/read.html.twig', [
            'levels' => $levels,
        ]);
    }

    #[Route('/supprimer-niveau/{id}', name: 'delete')]
    public function delete(Level $level, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($level);
        $entityManager->flush();
        return $this->redirectToRoute('admin_level_read');
    }
}
