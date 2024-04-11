<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DocumentRepository $documentRepository, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            $nbDocuments = $documentRepository->count();
            $nbDownloads = $documentRepository->countDownloadNumber()['nbDownloads'];
            $topDownloadsDocuments = $documentRepository->findTopDownloadsDocuments();
            $topRatingsDocuments = $documentRepository->findTopRatingsDocuments();
            $nbUsers = $userRepository->count();
            return $this->render('app/home_connected.html.twig', [
                'nbDocuments' => $nbDocuments,
                'nbDownloads' => $nbDownloads,
                'nbUsers' => $nbUsers,
                'topDownloadsDocuments' => $topDownloadsDocuments,
                'topRatingsDocuments' => $topRatingsDocuments,
            ]);
        }

        return $this->render('app/home.html.twig');
    }

    #[Route('/a-propos-de-nous', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('app/about.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/accessibilite', name: 'app_accessibility')]
    public function accessibility(): Response
    {
        return $this->render('app/accessibility.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/nous-contacter', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                // ->from(new Address('admin@teachinghub.fr'))
                ->from(new Address('contact@lapinou.tech'))
                // ->to(new Address('contact@teachinghub.fr'))
                ->to(new Address('p.coelho@lapinou.tech'))
                ->replyTo($form->getData()->getEmail())
                ->subject('Nouvelle prise de contact sur TeachingHub')
                ->text('Message de la part de' . $form->getData()->getFirstName() . ' ' . $form->getData()->getLastName() . ' : ' . $form->getData()->getMessage());

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a bien été envoyé. Nous reviendrons vers vous très prochainement.'
            );
        }
        return $this->render('app/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }

    #[Route('/mentions-legales', name: 'app_legal_mentions')]
    public function legalMentions(): Response
    {
        return $this->render('app/legal_mentions.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/politique-de-confidentialite', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('app/privacy.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}
