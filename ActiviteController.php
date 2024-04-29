<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;

use App\Entity\Activite;
use App\Form\ActiviteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/activite')]
class ActiviteController extends AbstractController
{


    #[Route('/puzzle', name: 'app_puzzle2')]
    public function index2(): Response
    {
        return $this->render('puzzle/index.html.twig');
    }


    #[Route('/search', name: 'app_activite_search', methods: ['GET'])]
    public function search(Request $request, ActiviteRepository $repository): Response
    {
        $query = $request->query->get('query');
        $activites = $repository->findBySearchQuery($query);

        return $this->json([
            'activites' => $activites // Ensure that $activites is an array of associative arrays or objects
        ]);
    }
    

    

// This route displays the Activite gallery in client home !!!
#[Route('/gallery', name: 'activite_gallery', methods: ['GET'])]
public function gallery(EntityManagerInterface $entityManager): Response
{
    $activites = $entityManager->getRepository(Activite::class)->findAll();

    return $this->render('activite/gallery.html.twig', [
        'activites' => $activites,
    ]);
}





    #[Route('/', name: 'app_activite_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $activites = $entityManager
            ->getRepository(Activite::class)
            ->findAll();

        return $this->render('activite/index.html.twig', [
            'activites' => $activites,
        ]);
    }

    #[Route('/new', name: 'app_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            
            /** @var UploadedFile $file */
            $file = $form->get('posterUrl')->getData();
            
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('posters_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $activite->setPosterurl($newFilename);
            }

            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('app_activite_index');
        }

        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idActivite}', name: 'app_activite_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/{idActivite}/edit', name: 'app_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Activity updated successfully!');
            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{idActivite}', name: 'app_activite_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getIdActivite(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        
        $this->addFlash('success', 'Activity has been deleted successfully!');

    }else {
        $this->addFlash('error', 'Invalid CSRF token, could not delete the activity.');
    }

        return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
}






#[Route('/details/{idActivite}', name: 'activity_details', methods: ['GET'])]
public function activityDetails(int $idActivite, EntityManagerInterface $entityManager): Response
{
    $activite = $entityManager->getRepository(Activite::class)->find($idActivite);

    if (!$activite) {
        throw $this->createNotFoundException('The activity does not exist.');
    }

    return $this->render('activite/details.html.twig', [
        'activite' => $activite
    ]);
}

}