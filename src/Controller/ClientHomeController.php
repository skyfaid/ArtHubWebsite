<?php

namespace App\Controller;

use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientHomeController extends AbstractController
{
    #[Route('/', name: 'app_client_home')]
    public function index(): Response
    {
        return $this->render('ClientHome/index.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }
   
    #[Route('/home/events', name: 'app_client_event')]
    public function events(): Response
    {
        return $this->render('evenements/eventlist.html.twig', [
            'controller_name' => 'EvenementsController',
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
        return $this->render('ClientHome/ComplaintManagement/complaint.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }

    #[Route('/home/masterclass', name: 'app_client_masterclass')]
    public function masterclass(): Response
    {
        return $this->render('ClientHome/MasterClassManagement/classes.html.twig', [
            'controller_name' => 'ClientHomeController',
        ]);
    }
}
