<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PuzzleController extends AbstractController
{
    #[Route('/puzzle', name: 'app_puzzle')]
    public function index(): Response
    {
        return $this->render('puzzle/index.html.twig');
    }
}
