<?php
// src/Controller/QuizController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuizControllerAct extends AbstractController
{
    private $questions = [
        // Define your questions and options here
        [
            'question' => "Who painted the famous artwork 'Starry Night'?",
            'options' => ['Pablo Picasso', 'Leonardo da Vinci', 'Vincent van Gogh', 'Claude Monet'],
            'correct' => 2
        ],
        [
            'question' => "Which art movement was characterized by its focus on geometric shapes and bold colors?",
            'options' => ['Fauvism', 'Abstract Expressionism', 'Surrealism', 'Cubism'],
            'correct' => 3
        ],
        [
            'question' => "Who is considered the father of modern Tunisian painting?",
            'options' => ['Nja Mahdaoui', 'Hatem El Mekki', 'Ammar Farhat', 'Ali Bellagha'],
            'correct' => 2
        ],
        [
            'question' => "What is the name of the oldest museum in Tunisia, known for its extensive collection of Roman mosaics?",
            'options' => ['National Museum of Carthage', 'Bardo Museum', 'Museum of Islamic Art', 'Sidi Bou Said Museum'],
            'correct' => 1
        ],
        [
            'question' => "Who is the Tunisian artist known for his impressionist paintings capturing scenes of Tunisian daily life?",
            'options' => ['Ammar Farhat', 'Nja Mahdaoui', 'Aly Ben Salem', 'Hatem El Mekki'],
            'correct' => 2
        ],
        [
            'question' => "In which country did Vincent van Gogh spend the majority of his painting career?",
            'options' => ['France', 'Spain', 'Netherlands', 'Italy'],
            'correct' => 2
        ],
        [
            'question' => "What was Bob Ross's famous catchphrase that he often used during his painting demonstrations?",
            'options' => ['Painting with passion', 'Brush with greatness', 'Let\'s get creative!', 'Happy little clouds'],
            'correct' => 3,
            'image'=> '/images/Bob-Ross.jpg',
        ],
        [
            'question' => "Which of Vincent van Gogh's paintings is considered one of the most valuable artworks ever sold?",
            'options' => ['The Starry Night', 'Irises', 'Portrait of Dr. Gachet', 'Sunflowers'],
            'correct' => 2
        ],
        [
            'question' => "Where is the Van Gogh Museum located?",
            'options' => ['Paris, France', 'Amsterdam, Netherlands', 'Madrid, Spain', 'London, England'],
            'correct' => 1,
            'image'=> '/images/van-gogh-museum.jpg',
        ],
        [
            'question' => "Which famous museum houses Leonardo da Vinci's masterpiece, the Mona Lisa?",
            'options' => ['The Louvre', 'The Metropolitan Museum of Art', 'The British Museum', 'The Vatican Museums'],
            'correct' => 0,
            'image'=> '/images/mona-lisa.jpg',
        ],
    ];

    #[Route('/quiz', name: 'quiz_index', methods: ['GET', 'POST'])]
    public function index(SessionInterface $session): Response
    {
        $session->set('current_question', 0);
        $session->set('correct_answers', 0);

        return $this->redirectToRoute('quiz_question');
    }



    #[Route('/quiz/question', name: 'quiz_question', methods: ['GET', 'POST'])]
    public function question(SessionInterface $session): Response
    {
       
    

        $currentQuestionIndex = $session->get('current_question', 0);
        $question = $this->questions[$currentQuestionIndex];
    
        return $this->render('quiz/question.html.twig', [
            'question' => $question,
            'questionIndex' => $currentQuestionIndex,
            'totalQuestions' => count($this->questions)
        ]);
    }
    

   
     #[Route('/quiz/answer', name:'quiz_answer', methods:['POST'])]
     
    public function answer(Request $request, SessionInterface $session): Response
    {
        $chosenOption = $request->request->getInt('option');
        $currentQuestionIndex = $session->get('current_question', 0);

        if ($chosenOption === $this->questions[$currentQuestionIndex]['correct']) {
            $session->set('correct_answers', $session->get('correct_answers', 0) + 1);
        }

        if ($currentQuestionIndex + 1 < count($this->questions)) {
            $session->set('current_question', $currentQuestionIndex + 1);
            return $this->redirectToRoute('quiz_question');
        } else {
            return $this->redirectToRoute('quiz_result');
        }
    }

    
      #[Route('/quiz/result', name:'quiz_result')]
     
    public function result(SessionInterface $session): Response
    {
        $correctAnswers = $session->get('correct_answers', 0);
        $totalQuestions = count($this->questions);
 // Generate a remark based on the number of correct answers
 if ($correctAnswers < 2) {
    $remark = "Oh no..! You have failed the quiz. It seems that you need to improve your general knowledge. Practice daily! Check your results here.";
} elseif ($correctAnswers >= 2 && $correctAnswers < 5) {
    $remark = "Oops..! You have scored less marks. It seems like you need to improve your general knowledge. Check your results here.";
} elseif ($correctAnswers >= 5 && $correctAnswers <= 7) {
    $remark = "Good. A bit more improvement might help you to get better results. Practice is the key to success. Check your results here.";
} elseif ($correctAnswers == 8 || $correctAnswers == 9) {
    $remark = "Congratulations! Its your hardwork and determination which helped you to score good marks. Check your results here.";
} elseif ($correctAnswers == 10) {
    $remark = "Congratulations! You have passed the quiz with full marks because of your hardwork and dedication towards studies. Keep it up! Check your results here.";
}
$session->clear();  // Clear the session after displaying the results
        return $this->render('quiz/result.html.twig', [
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions,
            'remark'         => $remark
        ]);
    }
}