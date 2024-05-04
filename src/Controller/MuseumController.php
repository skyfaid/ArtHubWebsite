<?php
// src/Controller/MuseumController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MuseumController extends AbstractController
{
     #[Route('/museum', name:'museum',methods:['GET'])]
    public function index(): Response
    {
        return $this->render('activite/museum.html.twig');
    }
}
