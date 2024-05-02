<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Form\Oeuvre1Type;
use App\Repository\OeuvreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionsController extends AbstractController
{
    #[Route('/collectionsoeuvre', name: 'collections')]
    public function index(OeuvreRepository $oeuvreRepository,Request $request,EntityManagerInterface $entityManager): Response
    {
        $search = $request->query->get('search');
        $oeuvres = $search ? $oeuvreRepository->findByTitleLike($search) : $oeuvreRepository->findAll();
        
       
        $updateForms = [];
        
        foreach ($oeuvres as $oeuvre) {
            $form = $this->createForm(Oeuvre1Type::class, $oeuvre);
    
            // Check if the form for this specific oeuvre was submitted
            if ($request->request->has('oeuvre1type') && $request->request->get('oeuvre1type')['id'] == $oeuvre->getId()) {
                $form->handleRequest($request);
    
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->flush();
                    $this->addFlash('success', 'Oeuvre updated successfully.');
                    return $this->redirectToRoute('collections');
                }
            }
    
            $updateForms[$oeuvre->getId()] = $form->createView();
        }
        return $this->render('oeuvre/oeuvre.html.twig', [
            'oeuvres' => $oeuvres,
        'updateForms' => $updateForms,
        ]);
       

    }
    

    #[Route('/oeuvre/{id}', name: 'oeuvre_show', requirements: ['id' => '\d+'])]

    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $oeuvre = $entityManager->getRepository(Oeuvre::class)->find($id);
    
        if (!$oeuvre) {
            throw $this->createNotFoundException('No oeuvre found for id '.$id);
        }
    
        return $this->render('oeuvre/oeuvre.html.twig', [
            'oeuvre' => $oeuvre
        ]);
    }
    #[Route('/oeuvre/delete/{id}', name: 'oeuvre_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $oeuvre = $entityManager->getRepository(Oeuvre::class)->find($id);

        if (!$oeuvre) {
            throw $this->createNotFoundException('No oeuvre found for id ' . $id);
        }

        // Vérifiez le token CSRF ici
        if ($this->isCsrfTokenValid('delete' . $oeuvre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($oeuvre);
            $entityManager->flush();

            $this->addFlash('success', 'Oeuvre successfully deleted.');
        }

        return $this->redirectToRoute('collections');
    }
// Dans votre contrôleur CollectionsController

#[Route('/oeuvre/update/{id}', name: 'oeuvre_update', methods: ['POST'])]
public function update(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(Oeuvre1Type::class, $oeuvre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('success', 'Oeuvre updated successfully.');
        return $this->redirectToRoute('collections');
    }

    // Rediriger vers la page d'index ou renvoyer une réponse d'erreur si nécessaire.
    return $this->redirectToRoute('collections');
}



}