<?php

namespace App\Controller;

use App\Entity\Formations;
use App\Form\FormationsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formations')]
class FormationsController extends AbstractController
{
    #[Route('/Dash', name: 'app_formationsDash', methods: ['GET'])]
    public function indexDash(EntityManagerInterface $entityManager): Response
    {
        $formations = $entityManager
            ->getRepository(Formations::class)
            ->findAll();

        return $this->render('formations/formationDash.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/', name: 'app_formations_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $formations = $entityManager
            ->getRepository(Formations::class)
            ->findAll();

        return $this->render('formations/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/new', name: 'app_formations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formations();
        $form = $this->createForm(FormationsType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formationsDash', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formations/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formations_show', methods: ['GET'])]
    public function show(Formations $formation): Response
    {
        return $this->render('formations/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_formations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formations $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationsType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formationsDash', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formations/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formations_delete', methods: ['POST'])]
    public function delete(Request $request, Formations $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formationsDash', [], Response::HTTP_SEE_OTHER);
    }
}
