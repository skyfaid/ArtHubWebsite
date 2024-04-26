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


#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
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
    
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $entityManager->getRepository(Utilisateurs::class)->find(1);
            if (!$utilisateur) {
                // If no utilisateur is found, handle it gracefully
                $this->addFlash('error', 'User not found. Cannot create a reclamation without a user.');
                return $this->redirectToRoute('app_reclamation_index');
            }
            $reclamation->setUtilisateur($utilisateur);
    
            $imageFile = $form->get('productPNG')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename)->lower();
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
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
                        $this->getParameter('uploads_directory'),
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

    
    #[Route('/{ReclamationID}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getReclamationID(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_reclamation_index');
    }
 
}
