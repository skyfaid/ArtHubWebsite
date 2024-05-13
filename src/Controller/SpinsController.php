<?php

namespace App\Controller;

use App\Entity\Spins;
use App\Entity\Evenements;
use App\Entity\EventAccess;
use App\Entity\Utilisateurs;
use App\Repository\SpinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SpinsController extends AbstractController
{
    #[Route('/spin', name: 'spin')]
    public function spin(SpinsRepository $spinsRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userId = $user->getUtilisateurId();
        if (!$spinsRepository->canUserSpin($userId)) {
            return $this->render('spins/error.html.twig', [
                'message' => 'You must wait 8 hours before spinning again.'
            ]);
        }

        $spinsRepository->recordSpin($userId);
        return $this->render('spins/success.html.twig', [
            'message' => 'Spin successful! Come back in 8 hours for your next chance.'
        ]);
    }

    #[Route('/load-roulette', name: 'load_roulette', methods: ['GET'])]
    public function loadRoulette(SpinsRepository $spinsRepository): Response
{
    $user = $this->getUser();
    if (!$user) {
        return $this->render('spins/error.html.twig', [
            'message' => 'You must be logged in to spin.'
        ]);
    }
        $userId = $user->getUtilisateurId();
        $canSpin = $spinsRepository->canUserSpin($userId);
        $timeLeft = $spinsRepository->getTimeUntilNextSpin($userId); // Make sure this returns time in seconds

    return $this->render('evenements/roulette_modal.html.twig', [
        'canSpin' => $canSpin,
        'timeLeft' => $timeLeft // Pass time left in seconds
    ]);
}



#[Route('/record-spin', name: 'record_spin', methods: ['POST'])]
public function recordSpin(SpinsRepository $spinsRepository, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    if (!$user) {
        return $this->json(['error' => 'Not logged in'], 401);
    }

    if (!$spinsRepository->canUserSpin($user->getUtilisateurId())) {
        return $this->json(['error' => 'You must wait 8 hours before spinning again.'], 429);
    }

    try {
        $spinsRepository->recordSpin($user->getUtilisateurId());
        $exclusiveEvents = $entityManager->getRepository(Evenements::class)->findExclusiveEvents();
        if (!empty($exclusiveEvents) && rand(1, 100) <= 50) { 
            $wonEvent = $exclusiveEvents[array_rand($exclusiveEvents)];
            $access = new EventAccess();
            $access->setUser($user);
            $access->setEvent($wonEvent);
            $entityManager->persist($access);
            $entityManager->flush();

            return $this->json([
                'success' => true, 
                'message' => 'Congratulations! You won access to ' . $wonEvent->getNom() . '.'
            ]);
        }
        else {
            return $this->json(['success' => false, 'message' => 'No win this time. Better luck next spin!']);
        }
    } catch (\Exception $e) {
        return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}

}