<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Repository\OeuvreRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionClientController extends AbstractController
{
    
    #[Route('/collection/client', name: 'app_collection_client')]
    public function index(OeuvreRepository $oeuvreRepository): Response
    {   $oeuvres = $oeuvreRepository->findAll();
        return $this->render('oeuvreclient/collections.html.twig', [
            'oeuvres' => $oeuvres,
        ]);
    }
    
 /**
 * @Route("/upload", name="image_upload", methods={"POST"})
 * @param Request $request
 */
public function uploadImage(Request $request, EntityManagerInterface $entityManager): Response
{
    $imageFile = $request->files->get('image');
    if ($imageFile) {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

        try {
            $imageFile->move(
                $this->getParameter('images_directory'), 
                $newFilename
            );
        } catch (FileException $e) {
            // Gérer l'exception
        }

        $oeuvre = new Oeuvre();
        $oeuvre->setPosterUrl($newFilename);

        $entityManager->persist($oeuvre);
        $entityManager->flush();

        // Redirection ou réponse appropriée après l'upload de l'image
        return $this->redirectToRoute('app_collection_client');
    }

}
 

}
