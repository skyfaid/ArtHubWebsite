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
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Utilisateurs;
use App\Form\ArticlesType;
use App\Form\EditArticleType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends AbstractController
{

    #[Route('/search', name: 'blog_search')]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('query');
        $articles = $this->getDoctrine()->getRepository(Articles::class)->findByTitleLike($query);

        return $this->json([
            'articles' => $articles // Make sure your Article entity is serializable or manually format this data
        ]);
    }

    #[Route('/blog', name: 'app_articles')]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $query = $entityManager->getRepository(Articles::class)->createQueryBuilder('a')->getQuery();

        $pagination = $paginator->paginate(
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Get current page number, default to 1
            3 // Limit of items per page
        );

        return $this->render('ClientHome/BlogManagement/articles-list.html.twig', [
            'pagination' => $pagination,
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
    public function edit(Request $request, int $id, ArticlesRepository $articlesRepository, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $article = $articlesRepository->find($id);
        $form = $this->createForm(ArticlesType::class, $article);
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


    /////////'class': 'contact-form-validated contact-one__form'
    #[Route('/submit', name: 'add_article')]
    public function createPost(Request $request, ManagerRegistry $managerRegistry, LoggerInterface $logger): Response
    {
        $em = $managerRegistry->getManager();
        $article = new Articles();
        $user = $this->getUser();
        $article->setUtilisateur($user);

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imagePath')->getData();

            if ($imageFile) {
                // Generate a unique name for the file and store it in the database
                $imageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $imageName . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $article->setImagePath("images/blog/" . $newFilename);

                // Move the file to the desired directory
                try {
                    $imageFile->move(
                        $this->getParameter('article_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    $logger->error('An error occurred while uploading the image file: ' . $e->getMessage());
                    $this->addFlash('error', 'An error occurred while uploading the image file.');
                    return $this->redirectToRoute('add_article');
                }
            }



            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_articles');
        }

        return $this->renderForm('ClientHome/BlogManagement/add-article.html.twig', [
            'form' => $form,
        ]);
    }
}
