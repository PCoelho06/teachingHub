<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubjectController extends AbstractController
{
    private $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function allSubjectsAction(): Response
    {
        $subjects = $this->subjectRepository->findAll();

        return $this->render('partials/_marquee-icons.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}
