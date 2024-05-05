<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class SmsController extends AbstractController
{
    private $client;
    private $twilioNumber;
    private $logger;

    public function __construct(ContainerBagInterface $params, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $accountSid = $params->get('twilio.account_sid');
        $authToken = $params->get('twilio.auth_token');
        $this->twilioNumber = $params->get('twilio.number');
        
        $this->client = new Client($accountSid, $authToken);
    }

    /**
     * @Route("/send-sms/{to}/{status}", name="send_sms")
     */
    public function sendStatusUpdateSms(string $to, string $status): Response
    {
        $message = "Your reclamation has been " . $status . ".";
        try {
            $this->client->messages->create(
                $to,
                [
                    'from' => $this->twilioNumber,
                    'body' => $message,
                ]
            );
            return new Response('SMS sent successfully');
        } catch (\Exception $e) {
            $this->logger->error(sprintf('SMS sending failed: %s', $e->getMessage()));
            return new Response('Failed to send SMS', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}