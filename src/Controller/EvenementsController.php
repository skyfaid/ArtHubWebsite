<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Service\PdfService;
use App\Entity\Utilisateurs;
use App\Form\EvenementsType;
use App\Service\DiscordNotifier;
use App\Repository\SpinsRepository;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



#[Route('/evenements')]
class EvenementsController extends AbstractController
{
    #[Route('/', name: 'app_evenements_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenements::class)
            ->findAll();

        return $this->render('evenements/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

  
  
    #[Route('/new', name: 'app_evenements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request); //this line binds the incoming request data to the form
        
    if ($form->isSubmitted() && $form->isValid()) {
       // Define the base path for uploads based on the server's filesystem
       $uploadBasePath = $this->getParameter('kernel.project_dir') . '\public\uploads';

        /** @var UploadedFile $posterFile */
        $posterFile = $form->get('posterurl')->getData();
        if ($posterFile) {
            $posterFileName = $this->generateUniqueFileName().'.'.$posterFile->guessExtension();
            try {
                $posterFile->move(
                    $uploadBasePath,
                    $posterFileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
             // Store the full filesystem path in the entity
             $evenement->setPosterurl($uploadBasePath . '\\' . $posterFileName);
        }

        /** @var UploadedFile $videoFile */
        $videoFile = $form->get('videourl')->getData();
        if ($videoFile) {
            $videoFileName = $this->generateUniqueFileName().'.'.$videoFile->guessExtension();
            try {
                $videoFile->move(
                    $uploadBasePath,
                    $videoFileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            // Store the full filesystem path in the entity
            $evenement->setVideourl($uploadBasePath . '\\' . $videoFileName);
        }

            $entityManager->persist($evenement);
            $entityManager->flush(); 

            return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenements/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

private function generateUniqueFileName(): string
{
    // This generates a unique name for the file to avoid overwriting existing files
    // uniqid() is based on the current time in microseconds, providing a unique string
    return uniqid();
}

 #[Route('/event{id}', name: 'app_evenements_show', methods: ['GET'])]
    public function show(int $id, EvenementsRepository  $evenementsRepository): Response
    {
        $evenement = $evenementsRepository->find($id);
        // Handle the case when the event is not found
        if (!$evenement) {
            throw $this->createNotFoundException('The event does not exist');
        }
        return $this->render('evenements/show.html.twig', [
            'evenement' => $evenement,
        ]);
    } 

    
   #[Route('/{id}/edit', name: 'app_evenements_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
{
    $originalPosterUrl = $evenement->getPosterurl();
    $originalVideoUrl = $evenement->getVideourl();

    $form = $this->createForm(EvenementsType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $uploadBasePath = $this->getParameter('kernel.project_dir') . '\public\uploads';

        // Poster File Handling
        /** @var UploadedFile $posterFile */
        $posterFile = $form->get('posterurl')->getData();
        if ($posterFile) {
            $posterFileName = $this->generateUniqueFileName().'.'.$posterFile->guessExtension();
            try {
                $posterFile->move($uploadBasePath, $posterFileName);
                $evenement->setPosterurl($uploadBasePath . '\\' . $posterFileName);
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
            }
        } else {
            $evenement->setPosterurl($originalPosterUrl);
        }

        // Video File Handling
        /** @var UploadedFile $videoFile */
        $videoFile = $form->get('videourl')->getData();
        if ($videoFile) {
            $videoFileName = $this->generateUniqueFileName().'.'.$videoFile->guessExtension();
            try {
                $videoFile->move($uploadBasePath, $videoFileName);
                $evenement->setVideourl($uploadBasePath . '\\' . $videoFileName);
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
            }
        } else {
            $evenement->setVideourl($originalVideoUrl);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('evenements/edit.html.twig', [
        'evenement' => $evenement,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_evenements_delete', methods: ['POST'])]
    public function delete(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
    } 
    
    #[Route('/{id}', name: 'app_evenements_delete_instantly', methods: ['POST'])]
    public function deletedirectly(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $data['_token'] ?? '')) {
            $entityManager->remove($evenement);
            $entityManager->flush();
    
            return new JsonResponse(['success' => true]);
        }
    
        return new JsonResponse(['error' => 'Invalid CSRF token'], 403);
    }


/*#[Route('/events/front', name: 'event_list', methods: ['GET'])]  the latest 
public function listEvents(EntityManagerInterface $entityManager, ParticipantsRepository $participantsRepository,SpinsRepository $spinsRepository): Response
{
    $evenements = $entityManager->getRepository(Evenements::class)->findAll();
    $nearestEvent = $entityManager->getRepository(Evenements::class)->findNearestNextEvent();

    $user = $this->getUser();
    $userId = $user ? $user->getUtilisateurId() : null;

    $participationStatus = [];
    foreach ($evenements as $evenement) {
        $participationStatus[$evenement->getId()] = $participantsRepository->isUserParticipating($evenement->getId(), $userId);
    }

    $countdown = null;
    if ($nearestEvent) {
        $now = new \DateTime();
        $countdown = $nearestEvent->getDatedebut()->diff($now)->format('%a days %h hours %i minutes %s seconds');
    }

    // Get time until the next spin is allowed
    $timeLeft = $userId ? $spinsRepository->getTimeUntilNextSpin($userId) : null;

    return $this->render('evenements/eventlist.html.twig', [
        'evenements' => $evenements,
        'nearestEvent' => $nearestEvent,
        'countdown' => $countdown,
        'participationStatus' => $participationStatus,
        'timeLeft' => $timeLeft // pass the time left to the template
    ]);
}*/

#[Route('/events/front', name: 'event_list', methods: ['GET'])]
public function listEvents(EntityManagerInterface $entityManager, ParticipantsRepository $participantsRepository, SpinsRepository $spinsRepository, EvenementsRepository $evenementsRepository): Response
{
    $evenements = $evenementsRepository->findAll();
    $nearestEvent = $evenementsRepository->findNearestNextEvent();

    $user = $this->getUser();
    $userId = $user ? $user->getUtilisateurId() : null;

    $participationStatus = [];
    $eventsToShow = [];

    foreach ($evenements as $evenement) {
        $isParticipating = $participantsRepository->isUserParticipating($evenement->getId(), $userId);
        $participationStatus[$evenement->getId()] = $isParticipating;

        // Check if the event is not exclusive or if the user has access to the exclusive event
        if (!$evenement->getIsExclusive() || ($evenement->getIsExclusive() && $evenementsRepository->userHasAccessToEvent($userId, $evenement->getId()))) {
            $eventsToShow[] = $evenement;
        }
    }

    $countdown = null;
    if ($nearestEvent) {
        $now = new \DateTime();
        $countdown = $nearestEvent->getDatedebut()->diff($now)->format('%a days %h hours %i minutes %s seconds');
    }

    // Get time until the next spin is allowed
    $timeLeft = $userId ? $spinsRepository->getTimeUntilNextSpin($userId) : null;

    return $this->render('evenements/eventlist.html.twig', [
        'evenements' => $eventsToShow,
        'nearestEvent' => $nearestEvent,
        'countdown' => $countdown,
        'participationStatus' => $participationStatus,
        'timeLeft' => $timeLeft // pass the time left to the template
    ]);
}


#[Route('/{id}/pdf', name: 'app_evenements_pdf', methods: ['GET'])]
public function eventPdf(int $id, EvenementsRepository $evenementsRepository, ParticipantsRepository $participantsRepository, PdfService $pdfService): Response
{
    $event = $evenementsRepository->find($id);
    if (!$event) {
        throw $this->createNotFoundException('The event does not exist');
    }

    $participants = $participantsRepository->findParticipantsByEvent($id);
    $pdfContent = $pdfService->generateEventPdf($id, $event, $participants);

    return new Response($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="event_' . $id . '.pdf"'
    ]);
}



    #[Route('/details/{id}', name: 'app_evenements_details', methods: ['GET'])]
    public function details(int $id, EvenementsRepository $evenementsRepository, ParticipantsRepository $participantsRepository): Response
    {
        $evenement = $evenementsRepository->find($id);
    
        if (!$evenement) {
            throw $this->createNotFoundException('The event does not exist.');
        }
    

       // Fetch the current user
       $user = $this->getUser();
         // If there is no logged-in user, handle this case (e.g., redirect or show a message)
    if (!$user) {
        // Optional: Redirect to login or give a flash message
        $this->addFlash('error', 'You need to be logged in to view details.');
        return $this->redirectToRoute('app_login');
    }

    $userId = $user->getUtilisateurId();  // use the current logged-in user's ID

       // Check if the user is participating in this event
       $participationStatus = $participantsRepository->isUserParticipating($id, $userId);
   
       return $this->render('evenements/details.html.twig', [
           'evenement' => $evenement,
           'participationStatus' => $participationStatus,
       ]);
   
    }
    

   


    #[Route('/events/participate/{id}', name: 'app_evenements_participate', methods: ['POST'])]
public function participate(int $id, EvenementsRepository $evenementsRepository, ParticipantsRepository $participantsRepository, DiscordNotifier $discordNotifier): Response {
    $user = $this->getUser();
    if ($user === null) {
        // Optionally handle cases where there is no authenticated user
        return $this->redirectToRoute('event_list');
    }

    $userId = $user->getUtilisateurId();  // Obtain the current logged-in user's ID

    if ($evenementsRepository->participateInEvent($id, $userId)) {
        $participantsRepository->addParticipant($id, $userId);
        // Send a Discord notification
        $event = $evenementsRepository->find($id);
        if ($event) {
             // Fetching user's first name, last name, and email
             $prenom = $user->getPrenom(); // User's first name
             $nom = $user->getNom();       // User's last name
             $email = $user->getEmail();   // User's email
             $message = sprintf("User %s %s with the email (%s) has participated in the event %s", $prenom, $nom, $email, $event->getNom());
            $discordNotifier->sendNotification($message);
        }







        return $this->redirectToRoute('event_list');
    }
    return $this->redirectToRoute('event_list');
}











  /*  #[Route('/events/participate/{id}', name: 'app_evenements_participate', methods: ['POST'])]
    public function participate(int $id, EvenementsRepository $evenementsRepository, ParticipantsRepository $participantsRepository): Response {
        if ($evenementsRepository->participateInEvent($id, 100)) {
            $participantsRepository->addParticipant($id, 100);
            return $this->redirectToRoute('event_list');
        }
        return $this->redirectToRoute('event_list');
    }*/
    
    /*#[Route('/events/quit/{id}', name: 'app_evenements_quit', methods: ['POST'])]
    public function quit(int $id, EvenementsRepository $evenementsRepository, ParticipantsRepository $participantsRepository): Response {
        if ($evenementsRepository->quitEvent($id, 100)) {
            $participantsRepository->removeParticipant($id, 100);
            return $this->redirectToRoute('event_list');
        }
        return $this->redirectToRoute('event_list');
    }*/


    // Method to handle quitting an event
#[Route('/events/quit/{id}', name: 'app_evenements_quit', methods: ['POST'])]
public function quit(int $id, EvenementsRepository $evenementsRepository, ParticipantsRepository $participantsRepository,DiscordNotifier $discordNotifier): Response {
    $user = $this->getUser();
    if ($user === null) {
        // Optionally handle cases where there is no authenticated user
        return $this->redirectToRoute('event_list');
    }

    $userId = $user->getUtilisateurId();  // Obtain the current logged-in user's ID

    if ($evenementsRepository->quitEvent($id, $userId)) {
        $participantsRepository->removeParticipant($id, $userId);


// Send a Discord notification
$event = $evenementsRepository->find($id);
if ($event && $user instanceof Utilisateurs) {
    $prenom = $user->getPrenom(); // User's first name
    $nom = $user->getNom();       // User's last name
    $email = $user->getEmail();   // User's email
    $message = sprintf("User %s %s with the email (%s) has quit the event %s", $prenom, $nom, $email, $event->getNom());
    $discordNotifier->sendNotification($message);

}

        return $this->redirectToRoute('event_list');
    }
    return $this->redirectToRoute('event_list');
}

    #[Route('/participants/{id}', name: 'app_evenements_participants', methods: ['GET'])]
    public function showParticipants(int $id, ParticipantsRepository $participantsRepository): Response
    {
        $participants = $participantsRepository->findParticipantsByEvent($id);

        return $this->render('evenements/participants.html.twig', [
            'participants' => $participants,
            'eventId' => $id
        ]);
    }






}



