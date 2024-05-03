
<?php


use Knp\Snappy\Pdf;
use App\Entity\Reclamation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PPDFreclamation extends AbstractController
{
/**
 * @Route("/download/reclamation-pdf", name="download_reclamation_pdf")
 */
public function downloadSingleReclamationPdf(Pdf $snappy, $ReclamationID): Response
{


    $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($ReclamationID);

    if (!$reclamation) {
        throw $this->createNotFoundException('The reclamation does not exist');
    }

   $solution = $reclamation->getSolution();
   $html = $this->renderView('reclamation/pdf.html.twig', [
    'reclamation' => $reclamation,
    'solution' => $solution,
]);
    $pdfContent = $snappy->getOutputFromHtml($html);

    return new Response(
        $pdfContent,
        200,
        [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reclamation_report.pdf"'
        ]
    );
}
}