<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Form\Oeuvre1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/oeuvre')]
class OeuvreController extends AbstractController
{
    #[Route('/', name: 'app_oeuvre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $oeuvres = $entityManager
            ->getRepository(Oeuvre::class)
            ->findAll();

        return $this->render('oeuvre/oeuvre.html.twig', [
            'oeuvres' => $oeuvres,
        ]);
    }

    #[Route('/new', name: 'app_oeuvre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oeuvre = new Oeuvre();
        $form = $this->createForm(Oeuvre1Type::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oeuvre = $form->getData();
            
            $file = $form->get('posterurl')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // Vous pouvez renommer le fichier ici si nécessaire
                $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();
        
                // Déplacez le fichier dans le répertoire où les images sont stockées
                try {
                    $file->move(
                        $this->getParameter('images_directory'), // Vous devez définir ce paramètre
                        $newFilename
                    );
                  
                    
                    $oeuvre->setPosterUrl($newFilename);
    
                } catch (FileException $e) {
                    // ... gérer l'exception si quelque chose se produit pendant le téléchargement
                }
        
             
            }
        
            // ... sauvega
            $entityManager->persist($oeuvre);
            $entityManager->flush();

            return $this->redirectToRoute('collections', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvre/oeuvreform.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/image/{id}', name: 'app_serve_image')]
public function serveImage(int $id, EntityManagerInterface $entityManager): Response
{
    $oeuvre = $entityManager->getRepository(Oeuvre::class)->find($id);
    $imageData = $oeuvre->getPosterUrl(); // Cette méthode devrait retourner les données binaires de l'image

    if (!$imageData) {
        throw $this->createNotFoundException('Image not found.');
    }

    $response = new Response(stream_get_contents($imageData));
    $response->headers->set('Content-Type', 'image/jpeg'); // Assurez-vous que le type MIME correspond au type de l'image

    return $response;
}


    #[Route('/{id}', name: 'app_oeuvre_show', methods: ['GET'])]
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/oeuvre.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_oeuvre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Oeuvre1Type::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_oeuvre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvre/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_oeuvre_delete', methods: ['POST'])]
    public function delete(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($oeuvre);
            $entityManager->flush();
            $this->addFlash('success', 'Oeuvre deleted successfully.');
        }

        return $this->redirectToRoute('app_oeuvre_index', [], Response::HTTP_SEE_OTHER);
    }
}
