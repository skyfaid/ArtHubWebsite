<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/evenements')]
class EvenementsController extends AbstractController
{
    #[Route('/', name: 'app_evenements_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenements::class)
            ->findAll();

        return $this->render('evenements/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/new', name: 'app_evenements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request); //this line binds the incoming request data to the form

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenements/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenements_show', methods: ['GET'])]
    public function show(Evenements $evenement): Response
    {
        return $this->render('evenements/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenements/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenements_delete', methods: ['POST'])]
    public function delete(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/upload/poster', name: 'app_evenements_upload_poster', methods: ['POST'])]
public function uploadPoster(Request $request): JsonResponse
{
    /** @var UploadedFile $file */
    $file = $request->files->get('posterFile'); // Ensure 'posterFile' matches the key used in FormData

    if (!$file) {
        return new JsonResponse(['error' => 'No file provided'], Response::HTTP_BAD_REQUEST);
    }

    $uploadDirectory = $this->getParameter('uploads_directory'); // Define this parameter in your services.yaml
    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();

    try {
        $file->move($uploadDirectory, $newFilename);

        // Generate a URL to the uploaded file
        $fileUrl = $request->getSchemeAndHttpHost() . '/uploads/' . $newFilename;

        return new JsonResponse(['filePath' => $fileUrl]);
    } catch (FileException $e) {
        return new JsonResponse(['error' => 'Failed to upload file'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
#[Route('/upload/video', name: 'app_evenements_upload_video', methods: ['POST'])]
public function uploadVideo(Request $request): JsonResponse
{
    /** @var UploadedFile $file */
    $file = $request->files->get('videoFile'); // Make sure 'videoFile' matches the key used in your FormData in the JavaScript

    if (!$file) {
        return new JsonResponse(['error' => 'No file provided'], Response::HTTP_BAD_REQUEST);
    }

    $uploadDirectory = $this->getParameter('uploads_directory'); // Ensure this parameter is defined in your services.yaml
    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension(); // You might want to handle extensions more carefully, especially for video files

    try {
        $file->move($uploadDirectory, $newFilename);

        // Generate a URL to the uploaded file
        $fileUrl = $request->getSchemeAndHttpHost() . '/uploads/' . $newFilename;

        return new JsonResponse(['filePath' => $fileUrl]);
    } catch (FileException $e) {
        return new JsonResponse(['error' => 'Failed to upload file'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}










}
