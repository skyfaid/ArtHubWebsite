<?php

namespace App\Controller;
use App\Entity\Vente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DashadminController extends AbstractController
{
    #[Route('/dashadmin', name: 'app_dashadmin')]
    public function index( Request $request, VenteRepository $venteRepository, EntityManagerInterface $entityManager): Response
    {
        $search = $request->query->get('search');
        if ($search) {
            $ventes = $venteRepository->findBySearch($search);
        } else {
            $ventes = $venteRepository->findAll();
            foreach ($ventes as $vente) {
                $vente->setModepaiement("Carte");
            }
        }
    
        $totalVentes = count($ventes);
        $totalPrixVente = array_sum(array_map(function($vente) {
            return $vente->getPrixvente();
        }, $ventes));
    
        // Total des ventes d'aujourd'hui
        $dateAujourdhui = new \DateTime(); // Date d'aujourd'hui
        $totalVentesAujourdhui = count($venteRepository->findBy(['datevente' => $dateAujourdhui]));
    
        $quantiteVendueTotale = array_sum(array_map(function($vente) {
            return $vente->getQuantite();
        }, $ventes));
        $monthlySalesData = $venteRepository->getMonthlySalesData();
        $topVentesParOeuvre = $venteRepository->findTopVentesParOeuvre();
        return $this->render('Oeuvre/dashadmin.html.twig', [
            'ventes' => $ventes,
            'totalVentes' => $totalVentes,
            'totalPrixVente' => $totalPrixVente,
            'totalVentesAujourdhui' => $totalVentesAujourdhui,
            'quantiteVendueTotale' => $quantiteVendueTotale,
            'monthlySalesData' => $monthlySalesData,
            'topVentesParOeuvre' => $topVentesParOeuvre,
        ]);
    }


#[Route('/vente/delete/{id}', name: 'vente_delete')]
public function delete(Request $request, Vente $vente): Response
{
    // Security check (for example, check if the user is logged in and has the right permissions)

    // Remove the vente entity
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($vente);
    $entityManager->flush();

    // Add flash message to show success
    $this->addFlash('success', 'Vente deleted successfully.');

    // Redirect to the list
    return $this->redirectToRoute('app_dashadmin');
}



}
