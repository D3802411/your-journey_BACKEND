<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    //List all users
    #[Route('/user', name: 'app_user')] 
    public function index(UserRepository $userRepository): JsonResponse //Response??? it was the automatic one
    {   $users = $userRepository->findAll(); //fetch all the users
        $data = array_map(function(User $user) {  //make an array of all the users
                return [
                    "id" => $user->getId(),
                    "email" => $user->getEmail(),
                    "roles" => $user->getRoles(),
                    "password" => $user->getPassword(),
                    "firstName" => $user->getFirstName(),
                    "lastName" => $user->getLastName(),
                    "username" => $user->getUsername(),
                ];
        }, $users); 

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    //show one specific user
    #[Route('/user/{id}', name: 'app_user{id}')] 
    public function show(User $user): JsonResponse {
        $data = [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "roles" => $user->getRoles(),
            "password" => $user->getPassword(),
            "firstName" => $user->getFirstName(),
            "lastName" => $user->getLastName(),
            "username" => $user->getUsername(),
        ];
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }


    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse {
        //fetch json data of request
        $data = json_decode($request->getContent(), true );

        //create new user
        $user = new User();

        $user->setEmail($data ["email"]);
        $user->setRoles($data ["roles"] ?? ["ROLE_USER"]);
        $user->setPassword($data ["password"]);
        $user->setFirstName($data ["firstName"]);
        $user->setLastName($data ["lastName"]);
        $user->setUsername($data ["username"]);
        
        //record the new user in the DB
        $em->persist($user);
        $em->flush();

        return new JsonResponse(["status" => "User created"], JsonResponse::HTTP_CREATED);
    }


}


