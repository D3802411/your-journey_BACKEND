<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/article')]
final class ArticleController extends AbstractController
{

    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {   // Get the currently logged-in user to select what buttons will appear
        $user = $this->getUser();
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'user' => $user,
        ]);
    }
 
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
   
        $article = new Article();  // This line is creating a new instance of the Article entity. $article object is initialized as a new, empty instance. It doesn’t have any data yet, but it's ready to be filled with form data when the user submits the form.
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Sets, assigns the user who created the article and retrives it (meaning: access the currently logged-in user)
            $article->setUser($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash("Success", "The article has been created");

            return $this->redirectToRoute("app_article_index", [], Response::HTTP_SEE_OTHER);
        }
    return $this->render("article/new.html.twig", [
            "article" => $article,
            "form" => $form,
        ]);
    }
    

   #[Route('/{slug}-{id}/show', name: 'app_article_show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'], methods: ['GET', 'POST'])]
   public function show(Article $article, Request $request, SluggerInterface $slugger, int $id, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
   {    
        // Fetch comments for the article. Done here because it does not have to be on a different route
        $comments = $commentRepository->findCommentsByArticle($id);
        // Create the comment form
        $comment = new Comment();
        // declare the comment form! without this it won't work!
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        // Get the currently logged-in user: if it's here, do I still have to add it in every if?
        $user = $this->getUser();
        $title = $article->getTitle(); // Get the title from the article entity
        $slug = strtolower($slugger->slug($article->getTitle())->toString());
        //$slug = $slugger->slug($article->getTitle())->toString();
        // Fetch the article from the database
        $article = $entityManager->getRepository(Article::class)->findOneBy([
            'id' => $id,
            'slug' => $slug,
        ]);

            //comment submission
            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setArticle($article); // Associate the comment with the article
                if ($user) {
                    $comment->setUser($user);  // Set the user who is submitting/editing/deleting the comment
                    $this->isGranted('ROLE_ADMIN');
                } else {
                    //Redirect to the login page if the user is not authenticated
                    $this->addFlash('Join us!', 'Register or login to leave your comment');
                    return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
                  }    
                $entityManager->persist($comment);
                $entityManager->flush();
            // Redirect to the same page to show the new comment
            return $this->redirectToRoute('app_article_show', ['slug' => $slug,'id' => $article->getId()], Response::HTTP_SEE_OTHER);
            }

            //comment edition: see route below
            //comment deletion: see route below
         
     
        return $this->render('article/show.html.twig', [
            //here are listed the variables that will be returned in the twig, for twig to recognise them    
                'article' => $article,
                'user' => $user,
                'comments' => $comments,
                'comment' => $comment,
                'form' => $form->createView(),
                ]);
    }        


    #[Route('/_delete_comment/{id}/json', name: 'app_article_delete_comment', methods: ['POST'])]
    public function deleteComment(Comment $comment, EntityManagerInterface $entityManager): JsonResponse
    {        
            //see if the user may delete the comment 
            $user = $this->getUser();
            if (!$this->isGranted('ROLE_ADMIN') && $comment->getUser() !== $user) {
                throw $this->createAccessDeniedException('You do not have permission to delete this comment.');
            }        
                $entityManager->remove($comment);
                $entityManager->flush();
                //$this->addFlash('success', 'Comment deleted successfully!');

        //return $this->redirectToRoute('app_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        return new JsonResponse (['Message' => 'Comment deleted successfully!']);
    }

    
    #[Route('/_edit_comment/{id}', name: 'app_article_edit_comment', methods: ['GET', 'POST'])]
    public function editComment(Request $request, Comment $comment, int $id, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
    {   
        $comment = $commentRepository->find($id);  //fetch a comment by its id
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_article_show', ['slug' => $comment->getArticle()->getSlug(),'id' => $comment->getArticle()->getId()]);
        }

        return $this->render('article/_edit_comment.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }


    #[Route('/{slug}-{id}/edit', name: 'app_article_edit', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }


    #[Route('/{slug}-{id}', name: 'app_article_delete', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'], methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'app_article_search', methods: ['GET','POST'])]
    public function search(Request $request, ArticleRepository $articleRepository): Response
    {   
        // Create the search form   DO I STILL NEED THIS
        $form = $this->createForm(SearchArticleType::class); 
        // Handle the form submission
        $form->handleRequest($request);

        //$articles = [];  // Initialize an empty array to store search results

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the search data
            $query = $form->getData();
            // Fetch articles using the Article repository 
            //$articles = $articleRepository->findBySearchQuery($query);
            // NEW PAGE WITH RESULTS is INDEED a new route, as it's gives back an array of results
            return $this->redirectToRoute('app_article_search_results', $query); //[], Response::HTTP_SEE_OTHER);
        }
        return $this->render('article/search.html.twig', [
           'form' => $form,
           //'articles' => $articles,  // Pass the results to the template
        ]);
    }

    #[Route('/search_results', name: 'app_article_search_results', methods: ['GET'])]
    public function displayResults(Request $request, ArticleRepository $articleRepository): Response
    {   // Retrieve the search criteria from the query parameters
        $query = $request->query->all(); //MAYBE HERE THE ISSSUE: NOT ALL BUT QUERY RESULT! look up in the docs for query methods, like findResult...
    
        // Fetch articles using the repository method
        $articles = $articleRepository->findBySearchQuery($query);
        return $this->render('article/search_results.html.twig', [
            'articles' => $articles,  // Pass the results to the template
         ]);

    }

}
