<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\PasswordEditType;
use App\Form\UserBiographyType;
use App\Form\UserSupportLinkType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/mon-compte', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/mon-tableau-de-bord', name: 'dashboard')]
    public function dashboard(): Response
    {
        return $this->render('user/dashboard.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/mon-profil', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/modifier-mon-mot-de-passe', name: 'password_edit')]
    public function editPassword(#[CurrentUser] User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PasswordEditType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$userPasswordHasher->isPasswordValid($user, $form->get('current_password')->getData())) {
                $this->addFlash(
                    'danger',
                    'Le mot de passe actuel est incorrect'
                );
            } else {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('new_password')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );
            }
        }

        return $this->render('user/edit-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier-mes-informations', name: 'profile_edit')]
    public function editProfile(#[CurrentUser] User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Les modifications ont bien été enregistrées.'
            );

            $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-mon-compte', name: 'account_delete')]
    public function accountDelete(#[CurrentUser] User $user, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, Security $security): Response
    {
        $submittedToken = $request->getPayload()->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            $email = (new TemplatedEmail())
                ->from(new Address('gestion-utilisateurs@partageprof.fr', 'PartageProf Gestion Utilisateurs'))
                ->to($user->getEmail())
                ->subject('Nous sommes tristes de vous voir partir 😢')
                ->htmlTemplate('user/delete-account-email.html.twig');

            $mailer->send($email);

            $security->logout(false);

            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        $this->addFlash('danger', 'Une erreur est survenue lors de la tentative de suppression de votre compte. Veuillez réessayer en cliquant sur le lien dans votre tableau de bord.');

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/ma-biographie', name: 'biography_edit')]
    public function editBiography(#[CurrentUser] User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserBiographyType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre biographie a bien été modifiée.'
            );

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/biography-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/lien-de-support', name: 'support_link')]
    public function supportLink(#[CurrentUser] User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserSupportLinkType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre lien de support a bien été enregistré.'
            );

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/support-link.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
