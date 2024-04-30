<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserAdminType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/administration/utilisateurs', name: "admin_user_")]
class UserCrudController extends AbstractController
{
    #[Route('/editer-utilisateur/{id}', name: 'update')]
    public function update(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_user_read');
        }
        return $this->render('admin/user/handle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'read')]
    public function read(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/user/read.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/supprimer-utilisateur/{id}', name: 'delete')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin_user_read');
    }
}
