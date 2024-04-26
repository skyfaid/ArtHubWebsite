<?php

namespace App\Controller;


use App\Entity\Solution;
use App\Form\SolutionType;
use App\Entity\Reclamation;
use App\Form\UserSignInType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    ]);}
    
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
public function dashboard(EntityManagerInterface $entityManager): Response
{
    $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();
    $solutions = $entityManager->getRepository(Solution::class)->findAll();

    return $this->render('solution/index.html.twig', [
        'reclamations' => $reclamations,
        'solutions' => $solutions,
    ]);
}
   
}
       

