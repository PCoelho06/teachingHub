<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/administration/matieres', name: "admin_subject_")]
class SubjectCrudController extends AbstractController
{
    #[Route('/nouvelle-matiere', name: 'create')]
    #[Route('/editer-matiere/{id}', name: 'update')]
    public function handle(Subject $subject = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        $edit = true;
        if (!$subject) {
            $subject = new Subject();
            $edit = false;
        }
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subject);
            $entityManager->flush();
            return $this->redirectToRoute('admin_subject_read');
        }
        return $this->render('admin/subject/handle.html.twig', [
            'form' => $form->createView(),
            'edit' => $edit,
        ]);
    }

    #[Route('/', name: 'read')]
    public function read(SubjectRepository $subjectRepository): Response
    {
        $subjects = $subjectRepository->findAll();
        return $this->render('admin/subject/read.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    #[Route('/supprimer-matiere/{id}', name: 'delete')]
    public function delete(Subject $subject, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($subject);
        $entityManager->flush();
        return $this->redirectToRoute('admin_subject_read');
    }
}
