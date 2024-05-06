<?php
// src/Controller/QrCodeController.php

namespace App\Controller;

use App\Entity\Activite;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QrCodeController extends AbstractController
{
    #[Route('/qr-code/{activityId}', name: 'generate_qr_code', methods: ['GET'])]
    public function generateQrCode(int $activityId): Response
    {
        // Retrieve the activity entity based on the provided ID
        $activity = $this->getDoctrine()->getRepository(Activite::class)->find($activityId);

        // Check if the activity exists
        if (!$activity) {
            throw $this->createNotFoundException('Activity not found');
        }

        // Construct the user info for the QR code
        $userInfo =  ', Start Date: ' . $activity->getDatedebut()->format('Y-m-d') . ', End Date: ' . $activity->getDatefin()->format('Y-m-d') . ', Number of Places: ' . $activity->getNbrePlaces();

        // Generate the QR code
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($userInfo)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->validateResult(false)
            ->build();
   // Clear all output buffers
   while (ob_get_level()) {
    ob_end_clean();
}
//qrcode

        // Save the QR code as an image
        $qrCodePath = $this->getParameter('kernel.project_dir') . '/public/images/qrcodes/qrcode_' . $activityId . '.png';
        $qrCode->saveToFile($qrCodePath);

        // Return the QR code image as a response
        return $this->file($qrCodePath);
    }
}
