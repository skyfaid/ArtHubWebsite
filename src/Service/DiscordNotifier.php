<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DiscordNotifier
{
    private $httpClient;
    private $webhookUrl;

    public function __construct(HttpClientInterface $httpClient, string $webhookUrl)
    {
        $this->httpClient = $httpClient;
        $this->webhookUrl = $webhookUrl;
    }

    public function sendNotification(string $message): void
    {
        $this->httpClient->request('POST', $this->webhookUrl, [
            'json' => ['content' => $message]
        ]);
    }
}
