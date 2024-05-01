<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/administration/types', name: "admin_type_")]
class TypeCrudController extends AbstractController
{
    #[Route('/nouveau-type', name: 'create')]
    #[Route('/editer-type/{id}', name: 'update')]
    public function handle(Type $type = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        $edit = true;
        if (!$type) {
            $type = new Type();
            $edit = false;
        }
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($type);
            $entityManager->flush();
            return $this->redirectToRoute('admin_type_read');
        }
        return $this->render('admin/type/handle.html.twig', [
            'form' => $form->createView(),
            'edit' => $edit,
        ]);
    }

    #[Route('/', name: 'read')]
    public function read(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository->findAll();
        return $this->render('admin/type/read.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/supprimer-type/{id}', name: 'delete')]
    public function delete(Type $type, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($type);
        $entityManager->flush();
        return $this->redirectToRoute('admin_type_read');
    }
}
