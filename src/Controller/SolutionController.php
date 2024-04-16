<?php
namespace App\Controller;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateurs;
use App\Entity\Solution;
use App\Form\SolutionType;
use App\Entity\Reclamation;


#[Route('/solution')]
class SolutionController extends AbstractController
{
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
    // Fetch the Reclamation using the reclamationId
    $reclamation = $entityManager->getRepository(Reclamation::class)->find($reclamationId);
    if (!$reclamation) {
        throw $this->createNotFoundException('No reclamation found for id ' . $reclamationId);
    }

    // Assuming you have a user with ID=1 for now as a placeholder
    $user = $entityManager->getRepository(Utilisateurs::class)->find(1);
    if (!$user) {
        $this->addFlash('error', 'No user found with ID 1');
        return $this->redirectToRoute('app_solution_index'); // Adjust the redirection as needed
    }

    $solution = new Solution();
    $solution->setUtilisateur($user); // Set the user
    $solution->setReclamation($reclamation); // Link the solution to the reclamation

    // Here, we are setting up the form without the 'reclamation' field
    // since it's already predetermined
    $form = $this->createForm(SolutionType::class, $solution);
    $form->handleRequest($request);

   
    if ($form->isSubmitted() && $form->isValid()) {
        if ($solution->getStatus() !== 'pending') {
            $reclamation->setStatus($solution->getStatus()); 
            $solution->setDateResolved(new \DateTime()); // Set the resolved date when the status is not 'pending'
        } else {
            $solution->setDateResolved(null); // Ensure the date is null when the status is 'pending'
        }
    
        $entityManager->persist($solution);
        $entityManager->persist($reclamation); // Make sure to persist the updated reclamation
        $entityManager->flush();
    
        return $this->redirectToRoute('app_solution_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('solution/new.html.twig', [
        'solution' => $solution,
        'form' => $form,
        'reclamation' => $reclamation, // Pass the reclamation to the view
    ]);
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
        if ($this->isCsrfTokenValid('delete'.$solution->getSolutionid(), $request->request->get('_token'))) {
            $entityManager->remove($solution);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_solution_index', [], Response::HTTP_SEE_OTHER);
    }
}
