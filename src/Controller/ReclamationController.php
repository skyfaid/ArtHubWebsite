<?php

namespace App\Controller;
use App\Entity\Oeuvre;
use App\Entity\Solution;
use App\Form\SolutionType;
use App\Entity\Reclamation;
use App\Entity\Utilisateurs;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Snappy\Pdf;



#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
   
    #[Route('/slide', name: 'app_reclamation_slide')]
    public function slider(): Response
    {
        return $this->render('slide/slider.html.twig');
    }
   
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $user = $this->getUser();
    
        if ($form->isSubmitted() && $form->isValid()) {
            //$utilisateur = $entityManager->getRepository(Utilisateurs::class)->find(1);
           // $utilisateur = $entityManager->getRepository(Utilisateurs::class)->find($->getId());
            if (!$user) {
                // If no utilisateur is found, handle it gracefully
                $this->addFlash('error', 'User not found. Cannot create a reclamation without a user.');
                return $this->redirectToRoute('app_reclamation_index');
            }
            $reclamation->setUtilisateur($user);
            
    
            $imageFile = $form->get('productPNG')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename)->lower();
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was an error uploading the file.');
                    // Optionally log the error
                }
    
                // Update the 'productPng' property to store the image file name
                $reclamation->setProductPng($newFilename);
            }
    
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            $this->addFlash('success', 'Reclamation added successfully.');
            return $this->redirectToRoute('app_reclamation_index');
        }
    
        return $this->renderForm('reclamation/new.html.twig', [
            'form' => $form,
        ]);
    }
    

    #[Route('/{ReclamationID}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }



    #[Route('/{ReclamationID}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // Create the form with the ReclamationType
        $form = $this->createForm(ReclamationType::class, $reclamation);
    
        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            $imageFile = $form->get('productPNG')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename)->lower();
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $reclamation->setProductPng($newFilename); // Update the 'productPng' property with the new filename
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was an error uploading the file.');
                    return $this->redirectToRoute('app_reclamation_edit', ['ReclamationID' => $reclamation->getReclamationID()]);
                }
            }
    
            // Update the reclamation entity with the form data
            $entityManager->flush();
    
            // Redirect to the reclamation show page after successful update
            return $this->redirectToRoute('app_reclamation_show', ['ReclamationID' => $reclamation->getReclamationID()]);
        }
    
        // Render the edit form template
        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{ReclamationID}/delete', name: 'app_reclamation_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$reclamation->getReclamationID(), $request->request->get('_token'))) {
        // Manually remove the associated Solution, if it exists
        $solution = $reclamation->getSolution();
        if ($solution) {
            $entityManager->remove($solution);
        }
        $entityManager->remove($reclamation);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_reclamation_index');
}




    #[Route('/{ReclamationID}/claim', name: 'app_reclamation_claim', methods: ['POST'])]
public function claim(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
{
    if ($reclamation->getStatus() == 'accepted') {
        // Process the refund here. The specifics depend on how your refund process is designed.
        // For example, updating the status of the reclamation or recording the refund transaction.

        // Assuming we simply mark the reclamation as "completed" after claiming the money
        $reclamation->setStatus('completed');
        $entityManager->flush();

        $this->addFlash('success', 'Your refund has been successfully claimed.');
    } else {
        $this->addFlash('error', 'This reclamation cannot be claimed at this time.');
    }

    return $this->redirectToRoute('app_reclamation_index');
}


#[Route('/{ReclamationID}/pdf', name: 'app_reclamation_pdf', methods: ['GET'])]
    public function downloadSingleReclamationPdf(Pdf $snappy, $ReclamationID): Response
    {
        // Fetch the reclamation using the ReclamationID
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($ReclamationID);

        // Check if the reclamation exists
        if (!$reclamation) {
            throw $this->createNotFoundException('The reclamation does not exist');
        }

        $solution = $reclamation->getSolution();

        // Render the view with the reclamation and image path
        $html = $this->renderView('reclamation/pdf.html.twig', [
            'reclamation' => $reclamation,
        'solution' => $solution,
        ]);

        // Generate the PDF from the rendered HTML
        $pdfContent = $snappy->getOutputFromHtml($html);

        // Return the PDF as a response
        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="reclamation_%s.pdf"', $ReclamationID)
            ]
        );
    }

    #[Route('/reclamation/statistics', name: 'reclamation_statistics')]
    public function statistics(EntityManagerInterface $entityManager): Response
    {
        $oeuvreData = $entityManager->getRepository(Reclamation::class)->countReclamationsByOeuvre();
    
        $titles = [];
        $counts = [];
        $totalCounts = 0; // Initialize total count
    
        foreach ($oeuvreData as $data) {
            $titles[] = $data['title'];
            $counts[] = $data['count'];
            $totalCounts += $data['count']; // Sum up all counts
        }
    
        // Check if total counts is zero to avoid division by zero
        if ($totalCounts > 0) {
            // Convert counts to percentages
            $percentages = array_map(function ($count) use ($totalCounts) {
                return ($count / $totalCounts) * 100;
            }, $counts);
        } else {
            // Avoid division by zero by setting percentages to zero if no reclamations exist
            $percentages = array_fill(0, count($counts), 0);
        }
    
        return $this->render('stat/statistics.html.twig', [
            'titles' => $titles,
            'counts' => $percentages  // Pass percentages instead of raw counts
        ]);
    }

}
