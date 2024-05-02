<?php

namespace App\Controller;

use App\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Knp\Snappy\Pdf;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    private $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    #[Route('/invoice/download/{id}', name: 'invoice_download')]
    public function downloadInvoice(VenteRepository $venteRepository, int $id): Response
    {
        // Recherche de l'entité Vente en fonction de l'ID fourni.
        $vente = $venteRepository->find($id);
        
        // Si la vente n'existe pas, une exception est levée.
        if (!$vente) {
            throw $this->createNotFoundException('La vente n\'existe pas.');
        }

        // Préparation du HTML à convertir en PDF à l'aide de la vue Twig.
        $html = $this->renderView('invoice/index.html.twig', [
            'vente' => $vente, 
        ]);

        // Création d'un nom de fichier pour le PDF.
        $filename = 'invoice-' . date('Y-m-d_H-i-s') . '.pdf';

        // Création d'une réponse HTTP avec le contenu PDF et des en-têtes appropriés.
        return new Response(
            $this->pdf->getOutputFromHtml($html),
            200,
            [
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => sprintf('attachment; filename="%s"', $filename),
            ]
        );
       
    }
}
