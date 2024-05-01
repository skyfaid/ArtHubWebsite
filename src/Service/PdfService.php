<?php

namespace App\Service;

use Knp\Snappy\Pdf;
use Twig\Environment;

class PdfService
{
    private $snappy;
    private $twig;

    public function __construct(Pdf $snappy, Environment $twig)
    {
        $this->snappy = $snappy;
        $this->twig = $twig;
    }

    public function generateEventPdf($eventId, $event, $participants)
    {
        $html = $this->twig->render('evenements/event_pdf.html.twig', [
            'event' => $event,
            'participants' => $participants
        ]);

        return $this->snappy->getOutputFromHtml($html);
    }
}
