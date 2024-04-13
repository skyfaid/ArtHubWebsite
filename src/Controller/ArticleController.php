<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;
use App\Entity\Articles;
use App\Entity\Utilisateurs;
use App\Form\ArticlesType;
use App\Form\EditArticleType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ArticlesRepository;

class ArticleController extends AbstractController
{

    #[Route('/blog', name: 'app_articles')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Articles::class)->findAll();
        return $this->render('ClientHome/BlogManagement/articles-list.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{articleId}/view', name: 'article_details')]
    public function articleDetails(int $articleId, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Articles::class)->find($articleId);
        return $this->render('ClientHome/BlogManagement/article-details.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}/delete', name: 'delete_article')]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $article->getArticleId(), $request->request->get('_token'))) {
        $entityManager->remove($article);
        $entityManager->flush();
        // }
        return $this->redirectToRoute('app_articles');
    }



    #[Route('/article/{id}/edit', name: 'edit_article')]
    public function edit(Request $request, int $id, ManagerRegistry $managerRegistry, ArticlesRepository $articlesRepository): Response
    {
        $entityManager = $managerRegistry->getManager();
        $article = $articlesRepository->find($id);
        $form = $this->createForm(EditArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Article updated successfully.'); // Flash message for success
            return $this->redirectToRoute('app_articles'); // Redirect to article list page
        }

        return $this->render('ClientHome/BlogManagement/edit-article.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /* {% if currentUser.id == article.utilisateur.id %}
                            <!-- Display edit and delete buttons only if current user is the author -->
                          <li><a href="{{ path('edit_article', {'id': article.articleId}) }}"><i class="far fa-edit"></i></a></li>
                            <li><a href="{{ path('delete_article', {'id': article.id}) }}"><i class="far fa-trash-alt"></i> Delete</a></li>
                        {% endif %}
    */


    #[Route('/article/add', name: 'add_article')]
    public function createPost(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $article = new Articles();
        $article->setUtilisateur($entityManager->getRepository(Utilisateurs::class)->find(9)); // Assuming Utilisateurs is your user entity
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info('Redirecting to app_articles route');
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('ClientHome/BlogManagement/add-article.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
