<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserSignInType;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('/AdminDash/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    #[Route('/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        $form = $this->createForm(UserSignInType::class);

    // Handle the request
    $form->handleRequest($request);

    // Render the template, passing in the form view
    return $this->render('login.html.twig', [
        'UserSignIn' => $form->createView(),
    ]);
}
       
}
