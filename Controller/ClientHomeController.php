<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Form\RegistrationType;

class ClientHomeController extends AbstractController
{

    #[Route('/', name: 'app_client_home')]
    public function index(): Response
    {
        return $this->render('ClientHome/index.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }
    #[Route('/test', name: 'app_client_test')]
    public function test(): Response
    {
        return $this->render('custom.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }
    #[Route('/register', name: 'app_client_register')]
    public function register(): Response
    {
        return $this->render('custom.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }

    #[Route('/login', name: 'app_client_login')]
    public function login(): Response
    {
        return $this->render('custom.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }

    #[Route('/home/blog', name: 'app_client_blog')]
    public function blog(): Response
    {
        return $this->render('ClientHome/BlogManagement/blog.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }

    #[Route('/home/events', name: 'app_client_event')]
    public function events(): Response
    {
        return $this->render('ClientHome/EventManagement/events.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }

    #[Route('/home/activities', name: 'app_client_activity')]
    public function activities(): Response
    {
        return $this->render('ClientHome/ActivityManagement/activities.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }

    #[Route('/home/collection', name: 'app_client_collection')]
    public function collection(): Response
    {
        return $this->render('ClientHome/ArtWorkManagement/arts.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }

#[Route('/home/contact', name: 'app_client_contact')]
public function contact(): Response
{
    return $this->redirectToRoute('app_reclamation_index');
}
    

    #[Route('/home/masterclass', name: 'app_client_masterclass')]
    public function masterclass(): Response
    {
        return $this->render('ClientHome/MasterClassManagement/classes.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }
}
