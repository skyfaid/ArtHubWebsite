<?php
namespace App\Controller;

use App\Service\QuizGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends AbstractController
{
    private QuizGeneratorService $quizService;

    public function __construct(QuizGeneratorService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function getQuizQuestion(): Response
    {
        $quizQuestion = $this->quizService->fetchQuizQuestion();

        // Render or return the quiz question as needed
        return $this->json($quizQuestion);
    }
}
