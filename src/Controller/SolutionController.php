<?php

namespace App\Controller;
use App\Service\TwilioClient;
use DateTime;
use App\Entity\Payment;
use Twilio\Rest\Client;
use App\Entity\Solution;
use App\Form\SolutionType;
use App\Entity\Reclamation;
use App\Entity\Utilisateurs;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/solution')]
class SolutionController extends AbstractController
{
    private $twilioClient;
    private $logger;


    public function __construct(Client $twilioClient, LoggerInterface $logger)
    { 
        $this->twilioClient = $twilioClient;  // Twilio Client injected through service configuration
        $this->logger = $logger;  
    }


    #[Route('/', name: 'app_solution_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $solutions = $entityManager->getRepository(Solution::class)->findAll();
        $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();
        return $this->render('solution/index.html.twig', [
            'solutions' => $solutions,
            'reclamations' => $reclamations
        ]);
    }
  
    #[Route('/new/{reclamationId}', name: 'app_solution_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, int $reclamationId): Response
{
    $reclamation = $entityManager->getRepository(Reclamation::class)->find($reclamationId);
    if (!$reclamation) {
        throw $this->createNotFoundException('No reclamation found for id ' . $reclamationId);
    }

    $user = $entityManager->getRepository(Utilisateurs::class)->find($reclamation->getUtilisateurId()); // Adjust as necessary for your user retrieval logic
   

    $solution = new Solution();
    $solution->setUtilisateur($user);
    $solution->setReclamation($reclamation);

    $form = $this->createForm(SolutionType::class, $solution);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($solution);
        $entityManager->persist($reclamation);

        if ($solution->getStatus() !== 'pending') {
            $reclamation->setStatus($solution->getStatus());
            $solution->setDateResolved(new \DateTime());
            $entityManager->flush();


                $smsMessage = '';
                if ($solution->getStatus() === 'accepted') {
                    $smsMessage = "Dear client, your Reclamation has been accepted. Check your account to claim your 💸.";
                } elseif ($solution->getStatus() === 'declined') {
                    $smsMessage = "Dear client, your Reclamation has been declined.";
                } else {
                    $smsMessage = "Your reclamation status is pending.";
                }
                $twilioSid = $_ENV['TWILIO_ACCOUNT_SID'];
                $twilioToken = $_ENV['TWILIO_AUTH_TOKEN'];
                $twilioFrom = $_ENV['TWILIO_FROM_NUMBER'];
                $twilioClient = new TwilioClient($twilioSid, $twilioToken, $twilioFrom);
                $twilioClient->sendSms("+216". $reclamation->getPhoneNumber(), $smsMessage);
    
                return $this->redirectToRoute('app_solution_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        
            return $this->renderForm('solution/new.html.twig', [
                'solution' => $solution,
                'form' => $form,
                'reclamation' => $reclamation
            ]);
        }   

private function sendSms($message,$numtel)
{
    try {
        $twilioNumber = $this->getParameter('twilio.number');
        $this->twilioClient->messages->create(
            $_ENV['TWILIO_TO_NUMBER'],
            [
                'from' => $twilioNumber,
                'body' => $message
            ]
        );
    } catch (\Exception $e) {
        $this->logger->error('Failed to send SMS: ' . $e->getMessage());
    }
}

    #[Route('/{solutionid}', name: 'app_solution_show', methods: ['GET'])]
    public function show(Solution $solution): Response
    {
        return $this->render('solution/show.html.twig', [
            'solution' => $solution,
        ]);
    }

    #[Route('/{solutionid}/edit', name: 'app_solution_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Solution $solution, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SolutionType::class, $solution);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            // Send an SMS notification about the update
            if ($solution->getReclamation() && $solution->getReclamation()->getPhoneNumber()) {
                $message = "Your reclamation solution has been updated. Status is now: " . $solution->getStatus();
                $this->sendSms($solution->getReclamation()->getPhoneNumber(), $message);
            }
    
            return $this->redirectToRoute('app_solution_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('solution/edit.html.twig', [
            'solution' => $solution,
            'form' => $form,
        ]);
    }

    #[Route('/{solutionid}', name: 'app_solution_delete', methods: ['POST'])]
    public function delete(Request $request, Solution $solution, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $solution->getSolutionid(), $request->request->get('_token'))) {
          
            $reclamation = $solution->getReclamation();
            if ($reclamation) {
                $reclamation->setStatus('pending');
                $entityManager->persist($reclamation);
            }
          
            $entityManager->remove($solution);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_solution_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/claim-solution/{solutionId}', name: 'app_solution_claim_form', methods: ['GET', 'POST'])]
    public function claimSolutionForm(Request $request, EntityManagerInterface $entityManager, int $solutionId): Response
    {
        $solution = $entityManager->getRepository(Solution::class)->find($solutionId);
        if (!$solution) {
            $this->addFlash('error', 'Solution not found.');
            return $this->redirectToRoute('app_solution_index');
        }
    
        if ($request->isMethod('POST')) {
            $submittedEmail = $request->request->get('paypalEmail');
            $payment = $entityManager->getRepository(Payment::class)->findOneBy(['payerEmail' => $submittedEmail]);
    
            if ($payment) {
                return $this->redirectToRoute('refund_success');
            } else {
                return $this->redirectToRoute('refund_error');
            }
        }
    
        return $this->render('refund/claim_form.html.twig', [
            'solution' => $solution,
        ]);
    }


    #[Route('/refund/success', name: 'refund_success')]
    public function refundSuccess(): Response
    {
        return $this->render('refund/success.html.twig', [
            'message' => 'Your claim has been successfully processed!'
        ]);
    }
    
#[Route('/refund/error', name: 'refund_error')]
public function refundError(): Response
{
    // Render the error page with an appropriate message
    return $this->render('refund/error.html.twig', [
        'message' => 'There was an error processing your claim. Please check the details and try again.'
    ]);
}}
