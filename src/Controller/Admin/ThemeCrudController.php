<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/administration/themes', name: "admin_theme_")]
class ThemeCrudController extends AbstractController
{
    #[Route('/nouveau-theme', name: 'create')]
    #[Route('/editer-theme/{id}', name: 'update')]
    public function handle(Theme $theme = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        $edit = true;
        if (!$theme) {
            $theme = new Theme();
            $edit = false;
        }
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($theme);
            $entityManager->flush();
            return $this->redirectToRoute('admin_theme_read');
        }
        return $this->render('admin/theme/handle.html.twig', [
            'form' => $form->createView(),
            'edit' => $edit,
        ]);
    }

    #[Route('/', name: 'read')]
    public function read(ThemeRepository $themeRepository): Response
    {
        $themes = $themeRepository->findAll();
        return $this->render('admin/theme/read.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/supprimer-theme/{id}', name: 'delete')]
    public function delete(Theme $theme, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($theme);
        $entityManager->flush();
        return $this->redirectToRoute('admin_theme_read');
    }
}
