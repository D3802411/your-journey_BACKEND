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
// use Symfony\Component\String\Slugger\SluggerInterface; for renaming photofiles


#[Route('/article')]
final class ArticleController extends AbstractController
{

    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
 
    /* #[Route('/api/article', name: 'app_article_api', methods: ['GET'])]
    public function getList(ArticleRepository $articleRepository): JsonResponse
                {
                    $articles = $articleRepository->findAll() ;
                    // $api4json = json_decode($articles) ;
                    $api4json = array_map(function (Article $article) {
                        return [
                            'id' =>$article->getid(),
                            'place' => $article->getPlace(),
                            'city' => $article->getCity(),
                            'country' => $article->getCountry(),
                            'attraction' => $article->getAttraction(),
                            'activity' => $article->getActivity(),
                            'title' => $article->getTitle(),
                        ];
                    }, $articles);
                    return new JsonResponse($api4json, JsonResponse::HTTP_OK); 
                } */


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
    

   #[Route('/{id}/show', name: 'app_article_show', methods: ['GET', 'POST'])]
   public function show(Article $article, Request $request, int $id, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
   {        
            // Fetch comments for the article
            $comments = $commentRepository->findCommentsByArticle($id);
            // Create the comment form
            $comment = new Comment();
            // declare the comment form! without this it won't work!
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

           
            if ($form->isSubmitted() && $form->isValid()) {
                 $comment->setArticle($article); // Associate the comment with the article
                 $user = $this->getUser();  // Assign the User to the Comment //$user = $security->getUser();
                    if ($user) {
                        $comment->setUser($user);  // Set the user who is submitting the comment

                    } else {
                     //Redirect to the login page if the user is not authenticated
                     // You can throw an exception or handle it differently
                    return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
                 }
                 $entityManager->persist($comment);
                 $entityManager->flush();
                // Redirect to the same page to show the new comment
                return $this->redirectToRoute('app_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
            }
            // Fetch comments associated with the article for deletion: already done above

            // Handle comment deletion
            /* if ($request->isMethod('POST') && $request->request->has('delete_comment')) {
                $commentId = $request->request->get('delete_comment');
                $comment = $entityManager->getRepository(Comment::class)->find($commentId);

                // Ensure the comment exists and belongs to the logged-in user
                if ($comment && $comment->getUser() === $this->getUser()) {
                    $entityManager->remove($comment);
                    $entityManager->flush();

                    $this->addFlash('success', 'Comment deleted successfully.');
                    return $this->render('article/show.html.twig', [
                        'article' => $article,
                        'comments' => $comments,
                    ]);
                }  
            } */
            return $this->render('article/show.html.twig', [  //here are listed the variables that I will use in twig, for twig to recognise them
                'article' => $article,
                'comments' => $comments,
                'form' => $form->createView(),
                ]);
            
   }


              
    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'app_article_search', methods: ['GET'])]
    public function search(Request $request, ArticleRepository $articleRepository): Response
    {   
        // Create the search form   DO I STILL NEED THIS
        $form = $this->createForm(SearchArticleType::class);
        // Handle the form submission
        $form->handleRequest($request);

        $articles = [];  // Initialize an empty array to store search results

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the search data
            $query = $form->getData();
            // Fetch articles using the Article repository 
            $articles = $articleRepository->findBySearchQuery($query);
            // NEW PAGE WITH RESULTS is INDEED a new route, as it's gives back an array of results
            return $this->redirectToRoute('app_article_search_results', [], Response::HTTP_SEE_OTHER);
        }

                
        // Build the $query array from request parameters
        // $query = [
        //    'place' => $request->query->get('place', ''),
         //   'city' => $request->query->get('city', ''),
        //    'country' => $request->query->get('country', ''),
        //    'attraction' => $request->query->get('attraction', ''),
        //    'activity' => $request->query->get('activity', ''),
        //    'title' => $request->query->get('title', ''),

        //];
       
        // Use the ArticleRepository to search articles
         //  $articles = $articleRepository->searchArticles($data); // Custom repository method
     
        return $this->render('article/search.html.twig', [
           'form' => $form->createView(),
           'articles' => $articles,  // Pass the results to the template
        ]);
    }

    #[Route('/search_results', name: 'app_article_search_results', methods: ['GET'])]
    public function displayResults(Request $request, ArticleRepository $articleRepository): Response
    {   // Retrieve the search criteria from the query parameters
        $query = $request->query->all();
        // Fetch articles using the repository method
        $articles = $articleRepository->findBySearchQuery($query);
        return $this->render('article/search_results.html.twig', [
            'articles' => $articles,  // Pass the results to the template
         ]);

    }

}
