<?php

namespace App\Controller;

use App\Repository\OeuvreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailsController extends AbstractController
{
    #[Route('/details/{id}', name: 'details')]
    public function index(OeuvreRepository $oeuvreRepository, int $id): Response
    {
        $oeuvre = $oeuvreRepository->find($id);
        
        if (!$oeuvre) {
            throw $this->createNotFoundException(
                'No oeuvre found for id '.$id
            );
        }
        // Vous pouvez ici passer des données dynamiques à votre vue si nécessaire
        return $this->render('OeuvreClient/details.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }
}
