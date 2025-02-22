<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Comment;


class ApiCommentController extends AbstractController
{
    #[Route('/api/comment', methods: ['GET'])]
    public function commentIndex(CommentRepository $commentRepository): JsonResponse  //why JSON? Because is a standard format for data exchange, supported by almost all programming languages
    {
        $comments = $commentRepository->findAll();
        /*to avoid CIRCULAR REFERENCE ERROR, I must use groups: in this way only certain properties 
        in the serialiser will be transferred to be encoded in json.I must tell Symfony's serializer to only include 
        properties that belong to the comments.index group. I'll do that by adding instructions as parameters*/
        return $this->json($comments, 200, [], [ 'groups' => ['comments.index']]); //REMEMBER 200=OK (HTTP status)
    }

    /*#[Route('/api/comment/{id}', ['id' => '\d+'], methods: ['GET'])]
    public function commentShow(Comment $comment): JsonResponse  //why JSON? Because is a standard format for data exchange, supported by almost all programming languages
    {
        return $this->json($comment, 200, [], [ 'groups' => ['comments.index']]); //REMEMBER 200=OK (HTTP status)
    } */
}
