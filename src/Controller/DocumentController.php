<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
use App\Entity\Document;
use App\Form\DocumentType;
use App\Data\SearchFilters;
use App\Form\DocumentSearchType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/documents', name: 'document_')]
class DocumentController extends AbstractController
{
    #[Route('/update-downloads/{id}', name: 'update_downloads')]
    public function updateDownloads(Document $document, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($document->getDownloaders()->contains($this->getUser())) {
            return $this->json($serializer->serialize(["proceed" => false, "message" => "Already downloaded"], 'json'));
        }

        $document->setDownloadsNumber($document->getDownloadsNumber() + 1);
        if ($this->getUser()) {
            $document->addDownloader($this->getUser());
        }

        $entityManager->persist($document);
        $entityManager->flush();

        return $this->json($serializer->serialize(["proceed" => true, "message" => "Downloaded"], 'json'));
    }

    #[Route('/get-filtered-documents', name: 'filtered')]
    #[Route('/chercher-un-document', name: 'search')]
    public function search(Request $request, DocumentRepository $documentRepository): Response
    {
        $filters = new SearchFilters();
        $offset = max(0, $request->query->getInt('offset', 0));

        $form = $this->createForm(DocumentSearchType::class, $filters);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offset = 0;
        }

        $paginator = $documentRepository->findBySearchCriteria($filters, $offset);

        if ($request->attributes->get('_route') == 'document_search') {
            return $this->render('document/search.html.twig', [
                'form' => $form->createView(),
                'documents' => $paginator,
                'previous' => $offset - DocumentRepository::DOCUMENTS_PER_PAGE,
                'next' => min(count($paginator), $offset + DocumentRepository::DOCUMENTS_PER_PAGE),
            ]);
        } else {
            return $this->render('document/results.html.twig', [
                'form' => $form->createView(),
                'documents' => $paginator,
                'previous' => $offset - DocumentRepository::DOCUMENTS_PER_PAGE,
                'next' => min(count($paginator), $offset + DocumentRepository::DOCUMENTS_PER_PAGE),
            ]);
        }
    }

    #[Route('/deposer-un-document', name: 'add')]
    #[Route('/editer-un-document/{slug}', name: 'update')]
    public function handleDocument(Document $document = null, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if (is_null($document)) {
            $document = new Document();
            $document->setDownloadsNumber(0);
            $edit = false;
        } else {
            $edit = true;
            if (!$this->getUser()) {
                $this->addFlash(
                    'danger',
                    'Attention, vous devez vous connecter pour modifier ce document.'
                );
                return $this->redirect('app_login');
            } else if ($this->getUser() != $document->getAuthor()) {
                $this->addFlash(
                    'danger',
                    'Attention, vous n\'avez pas les droits pour modifier ce document'
                );
                return $this->redirectToRoute('document_show', [
                    'slug' => $document->getSlug(),
                ]);
            }
        }

        $actionUrl = ($edit) ? $this->generateUrl('document_update', ['slug' => $document->getSlug()]) : $this->generateUrl('document_add');

        $form = $this->createForm(DocumentType::class, $document, options: [
            'action' => $actionUrl,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var SubmitButton $submitButton */

            $submitButton = $form->get('submit');
            if (!$submitButton->isClicked()) {
                return $this->render('document/handle.html.twig', [
                    'form' => $form,
                    'edit' => $edit,
                ]);
            }

            if ($form->isValid()) {

                if ($form->get('file')->getData() == null && $document->getFile() == null) {
                    $this->addFlash(
                        'danger',
                        'Vous devez ajouter un document pour continuer.'
                    );
                    return $this->render('document/handle.html.twig', [
                        'form' => $form,
                        'edit' => $edit,
                    ]);
                }

                $slug = $slugger->slug($document->getTitle());

                if ($form->get('file')->getData() != null) {
                    $file = $form->get('file')->getData();

                    $filename = uniqid() . '.' . $slug . '.' . $file->guessExtension();

                    try {
                        $file->move(
                            $this->getParameter('documents_directory'),
                            $filename
                        );
                    } catch (FileException $e) {
                        $this->addFlash(
                            'danger',
                            'Votre document n\'a pas pu être téléchargé. Si le problème persiste, merci de nous contacter.'
                        );

                        if (is_null($document->getId())) {
                            return $this->redirect('document_add');
                        } else {
                            return $this->redirectToRoute('document_update', [
                                'slug' => $document->getSlug(),
                            ]);
                        }
                    }

                    $document->setFile($filename);
                }

                if (!is_null($document->getId())) {
                    $document->setUpdatedAt(new DateTimeImmutable());
                } else {
                    $document->setUploadedAt(new DateTimeImmutable());
                }

                $document->setSlug($slug)
                    ->setAuthor($this->getUser());

                $entityManager->persist($document);
                $entityManager->flush();

                return $this->redirectToRoute('document_show', ['slug' => $slug]);
            }
        }

        return $this->render('document/handle.html.twig', [
            'form' => $form,
            'edit' => $edit,
        ]);
    }

    #[Route('/top-documents-telechargements', name: 'top_downloads')]
    public function topDownloads(DocumentRepository $documentRepository): Response
    {
        $topDownloads = $documentRepository->findTopDownloadsDocuments(20);

        return $this->render('document/top_documents.html.twig', [
            'documents' => $topDownloads,
            'title' => 'Top des documents les plus téléchargés',
        ]);
    }

    #[Route('/top-documents-notes', name: 'top_ratings')]
    public function topRatings(DocumentRepository $documentRepository): Response
    {
        $topRatings = $documentRepository->findTopRatingsDocuments(20);

        return $this->render('document/top_documents.html.twig', [
            'documents' => $topRatings,
            'title' => 'Top des documents les mieux notés',
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function showDocument(Document $document, DocumentRepository $documentRepository): Response
    {
        $suggestedDocuments = $documentRepository->findSuggestions($document);
        $sameAuthorDocuments = $documentRepository->findBySameAuthor($document);

        if ($this->getUser()) {
            foreach ($document->getComments() as $comment) {
                if ($comment->getAuthor() == $this->getUser()) {
                    $allreadyCommented = true;
                }
            }
        }

        return $this->render('document/show.html.twig', [
            'document' => $document,
            'suggestions' => $suggestedDocuments,
            'sameAuthorDocuments' => $sameAuthorDocuments,
            'allreadyCommented' => $allreadyCommented ?? false,
        ]);
    }

    #[Route('/supprimer-un-document/{slug}', name: 'delete')]
    public function delete(Document $document, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() != $document->getAuthor()) {
            $this->addFlash(
                'danger',
                'Attention, vous n\'avez pas les droits pour supprimer ce document'
            );
            return $this->redirectToRoute('document_show', [
                'slug' => $document->getSlug(),
            ]);
        }
        // supprimer le document du dossier upload
        unlink($this->getParameter('documents_directory') . '/' . $document->getFile());

        $entityManager->remove($document);
        $entityManager->flush();

        return $this->redirectToRoute('user_documents_show');
    }

    #[Route('/show-pdf/{slug}', name: 'show_pdf')]
    public function showPdf(Document $document): BinaryFileResponse
    {
        $response = $this->file($this->getParameter('documents_directory') . '/' . $document->getFile());
        $response->headers->set('Content-Type', 'application/pdf');
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_INLINE,
            $document->getFile()
        );
        $expires = new DateTime('tomorrow');
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Expires', $expires->format('D, d M Y H:i:s T'));

        return $response;
    }
}
