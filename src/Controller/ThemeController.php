<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\LevelRepository;
use App\Repository\SubjectRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Template;
use Symfony\Component\Routing\Annotation\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/theme')]
class ThemeController extends AbstractController
{
    #[Route('/ajouter-theme', name: 'theme_index')]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, LevelRepository $levelRepository, SubjectRepository $subjectRepository): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $name = $parameters['name'];
        $levels = $parameters['levels'];
        $subjects = $parameters['subjects'];

        $theme = new Theme();
        $theme->setName($name);

        foreach ($levels as $levelId) {
            $theme->addLevel($levelRepository->findOneBy(['id' => $levelId]));
        }

        foreach ($subjects as $subjectId) {
            $theme->addSubject($subjectRepository->findOneBy(['id' => $subjectId]));
        }

        $entityManager->persist($theme);
        $entityManager->flush();

        return $this->json($serializer->serialize(["proceed" => true, "message" => "Nouveau thème ajouté avec succès."], 'json'));
    }
}
