<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientHomeController extends AbstractController
{
    #[Route('/', name: 'app_client_home')]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }
}
