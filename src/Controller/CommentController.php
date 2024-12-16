<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
// Create a comment
#[Route('/comment/create/{articleId}', name: 'comment_create')]
public function create(Request $request, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, $articleId): Response
{$article = $articleRepository->find($articleId);
        
    if (!$article) {
        throw $this->createNotFoundException('Article not found');
    }

    $comment = new Comment();
    $comment->setArticle($article);

    $form = $this->createForm(CommentType::class, $comment);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($comment);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_article_show', ['id' => $articleId]); // Redirect to the article page
    }

    return $this->render('comment/create.html.twig', [
        'form' => $form->createView(),
        'article' => $article,
    ]);
}

// Edit a comment
#[Route('/comment/edit/{id}', name: 'comment_edit')]
public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
    
        $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        
        return $this->redirectToRoute('app_article_show', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
        'form' => $form->createView(),
        'comment' => $comment,
        ]);
    }

// Delete a comment
#[Route('/comment/delete/{id}', name: 'comment_delete')]
public function delete(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $articleId = $comment->getArticle()->getId();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_article_show', ['id' => $articleId]);
    }
    }
