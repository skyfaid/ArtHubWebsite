<?php

// src/Service/QuizGeneratorService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class QuizGeneratorService
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function fetchQuizQuestion(string $topic = "art"): array
    {
        try {
            $response = $this->client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => "gpt-4", // Use the latest model available
                    'messages' => [
                        ['role' => 'user', 'content' => "Generate another different question about $topic with multiple choice answers."]
                    ]
                ]
            ]);
            

            $content = $response->toArray();

            // Assuming the response has a structure where the first choice contains the desired text
            $text = $content['choices'][0]['message']['content'];

            // Placeholder: parse the generated text to extract the question and options
            return $this->parseQuestion($text);
        } catch (TransportExceptionInterface $e) {
            // Handle or log error appropriately
            throw new \RuntimeException('Failed to fetch quiz question from OpenAI: ' . $e->getMessage());
        }
    }


    public function fetchQuizAnswer(/*string $topic = "art"*/): array
    {
        try {
            $response = $this->client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => "gpt-4", // Use the latest model available
                    'messages' => [
                        ['role' => 'user', 'content' => "Generate the answer to the previous question ."]
                    ]
                ]
            ]);
            

            $content = $response->toArray();

            // Assuming the response has a structure where the first choice contains the desired text
            $text = $content['choices'][0]['message']['content'];

            // Placeholder: parse the generated text to extract the question and options
            return $this->parseQuestion($text);
        } catch (TransportExceptionInterface $e) {
            // Handle or log error appropriately
            throw new \RuntimeException('Failed to fetch quiz answer from OpenAI: ' . $e->getMessage());
        }
    }

    private function parseQuestion(string $text): array
    {
        
        // Example parser that needs actual implementation depending on response format
        // This is a dummy implementation and should be replaced with actual parsing logic
        return [
            'question' => substr($text, 0, strpos($text, '?') + 1),
            'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
            'correct' => 0,  // Determination of the correct answer would depend on the actual response
        ];
    }
}
