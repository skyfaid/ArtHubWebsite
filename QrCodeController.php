<?php
// src/Controller/QrCodeController.php

namespace App\Controller;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QrCodeController extends AbstractController
{
    /**
     * @Route("/qr-code/{activityId}", name="generate_qr_code")
     */
    public function generateQrCode(int $activityId): Response
    {
        // Presumably, you would fetch your activity based on the $activityId
        $activityUrl = $this->generateUrl('activity_details', ['id' => $activityId], UrlGeneratorInterface::ABSOLUTE_URL);

        // Generate QR code
        $qrCode = new QrCode($activityUrl);
        $qrCode->setSize(300);

        // Use the PngWriter to write the QR code to PNG format
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // The binary string of the image is retrieved here
        $qrCodeString = $result->getString();

        // Create the Response object with the PNG image data
        $response = new Response($qrCodeString);
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }
}
