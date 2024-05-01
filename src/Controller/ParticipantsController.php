<?php
namespace App\Controller;
use App\Entity\Participants;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParticipantsController extends AbstractController
{
    private $participantsRepository;

    public function __construct(ParticipantsRepository $participantsRepository)
    {
        $this->participantsRepository = $participantsRepository;
    }

  
    #[Route('/participant/delete/{id}', name: 'delete_participant', methods: ['POST'])]
    public function deleteParticipant(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        // Validate CSRF token if needed

        if ($participant = $this->participantsRepository->find($id)) {
            $eventId = $participant->getEvent()->getId(); // Get the event ID

            $entityManager->remove($participant);
            $entityManager->flush();

            // Redirect to the participants list page for the specific event
            return $this->redirectToRoute('app_evenements_participants', ['id' => $eventId], Response::HTTP_SEE_OTHER);
        } else {
            // Participant not found
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
    }
  
    
    
}