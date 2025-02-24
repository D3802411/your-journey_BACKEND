<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Comment;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ApiCommentController extends AbstractController
{
    #[Route('/api/comment', methods: ['GET'])]
    public function commentIndex(CommentRepository $commentRepository): JsonResponse  //why JSON? Because is a standard format for data exchange, supported by almost all programming languages
    {
            //i need to ask for the LAST COMMENT(not the index) to be found and then sent to the moderation service
        $comments = $commentRepository->findAll();
        /*to avoid CIRCULAR REFERENCE ERROR, I must use groups: in this way only certain properties 
        in the serialiser will be transferred to be encoded in json.I must tell Symfony's serializer to only include 
        properties that belong to the comments.index group. I'll do that by adding instructions as parameters*/
        //json method uses the SERIALISER to get data. Serialiser converts data in any format, x ex in Json
        return $this->json($comments, 200, [], [ 'groups' => ['comments.index']]); //REMEMBER 200=OK (HTTP status)
    }

    
    
    #[Route('/api/comment/{id}', ['id' => '\d+'], methods: ['GET', 'POST'])] //POST because I must send it to the moderation servcie
    public function commentModerate(CommentRepository $commentRepository, HttpClientInterface $httpClient): JsonResponse  //why JSON? Because is a standard format for data exchange, supported by almost all programming languages
    {         
                //i need to ask for the LAST COMMENT(not the index) to be found and sent to the moderation service
            $lastComment = $commentRepository->findOneBy([], ['id' => 'DESC']);
            //Send the comment content to the moderation service by injecting HttpClientInterface to make HTTP requests
            $moderationResponse = $httpClient->request('POST', 'https://moderation-api.example.com/moderate', [
                'json' => [
                'content' => $lastComment->getContent(),
                ]
            ]);

            // Handle the moderation service response,returns both the comment and the moderation result
        if ($moderationResponse->getStatusCode() !== 200) {
            return $this->json(['message' => 'Moderation service failed.'], 502);
        }
            
            return $this->json($lastComment, 200, [], ['groups' => ['comments.index']
        ]);

    }


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