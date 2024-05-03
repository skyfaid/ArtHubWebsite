<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Formations;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    private $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }
    
    #[Route('/pdf', name: 'app_pdf')]
public function index(Request $request): Response
{
    // Get the formation ID from the request query parameters
    $id = $request->query->get('id');

    // Fetch the formation from the database based on the ID
    $formation = $this->getDoctrine()->getRepository(Formations::class)->find($id);

    // If no formation found or ID not provided, return appropriate response
    if (!$formation || !$id) {
        return new Response('Formation not found or ID not provided.', Response::HTTP_NOT_FOUND);
    }

    // Generate PDF content for the formation
    $html = $this->renderView('pdf/index.html.twig', [
        'formation' => $formation
    ]);

    // Convert HTML content to PDF using KnpSnappyPdf service
    $pdfContent = $this->pdf->getOutputFromHtml($html);

    // Define PDF filename
    $filename = 'formation-' . $formation->getId() . '.pdf';

    // Return PDF as response with appropriate headers
    return new Response($pdfContent, Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"'
    ]);
}
}
