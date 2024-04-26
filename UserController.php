<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/dashboard/profile/{pseudo}', name: 'app_profile')]
    public function profile($pseudo): Response
    {
        return $this->render('/AdminDash/UserManagement/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/dashboard/users', name: 'app_users')]
    public function view(): Response
    {
        return $this->render('/AdminDash/UserManagement/users.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/dashboard/users', name: 'app_users')]
    public function ajouter(): Response
    {
        return $this->render('/AdminDash/UserManagement/users.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}