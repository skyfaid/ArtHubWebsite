<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Articles;

class ArticleController extends AbstractController
{

    #[Route('/home/blog', name: 'app_articles')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Fetch all articles from the database
        $articles = $entityManager->getRepository(Articles::class)->findAll();

        // Pass articles to the Twig template
        return $this->render('ClientHome/BlogManagement/articles-list.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route('home/blog/{articleId}', name: 'article_details')]
    public function articleDetails(int $articleId, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Articles::class)->find($articleId);

        return $this->render('ClientHome/BlogManagement/article-details.html.twig', [
            'article' => $article,
        ]);
    }
    #[Route('home/blog/article/add', name: 'article_new')]
    public function add_article(): Response
    {
        return $this->render('ClientHome/BlogManagement/add-article.html.twig', [
        ]);
    }
}
