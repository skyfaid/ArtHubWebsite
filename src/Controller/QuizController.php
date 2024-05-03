<?php
// src/Controller/QuizController.php

namespace App\Controller;

use App\Service\QuizGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuizController extends AbstractController
{
    private QuizGeneratorService $quizService;
    private SessionInterface $session;

    public function __construct(QuizGeneratorService $quizService, SessionInterface $session)
    {
        $this->quizService = $quizService;
        $this->session = $session;
    }

    public function getQuizQuestion(): Response
    {
        $quizData = $this->quizService->fetchQuizQuestion();
        $this->session->set('quizData', $quizData);  

        return $this->render('formations/quizz.html.twig', ['quiz' => $quizData]);
    }
 
    public function getQuizAnswer(): Response
    {
        $quizAnswer = $this->quizService->fetchQuizAnswer();
        $this->session->set('quizAnswer', $quizAnswer); 

        return $this->render('formations/quizz.html.twig', ['quiz' => $quizData]);
    }
    public function submitQuiz(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('quiz-item', $request->request->get('_csrf_token'))) {
            throw new \Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException();
        }

        $userAnswer = $request->request->get('answer');
        $quizData = $this->session->get('quizData');
        $quizAnswer = $this->session->get('quizAnswer');
        $formationId = $request->request->get('formation_id');
        if ($userAnswer === $quizAnswer) {
            $result = 'Correct!';
        } else {
            $result = 'Incorrect!';
        }

        return $this->redirectToRoute('app_formations_show', ['id' => $formationId]);
    }
}
