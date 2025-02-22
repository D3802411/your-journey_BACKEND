<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    /*ALL THIS NOT NEEDED NOW. USERS FOR NOW DONT SEE THEIR PROFILE, ADMIN DOES CRUD. FOR NEW USER, THERE IS REGISTRATION
    //List all users */
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
    /*#[Route('/user/{id}', name: 'app_user{id}')] 
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

    #[Route('/user/create', name: 'app_user_create')] 
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse {
        //fetch json data of request
        $data = json_decode($request->getContent(), true );

        //create new user
        $user = new User();

        $user->setEmail($data ["email"]);
        $user->setRoles($data ["roles"] ?? ["ROLE_USER"]);
        $user->setPassword($data ["password"]);
        $user->setUsername($data ["username"]);
        $user->setFirstName($data ["firstName"]);
        $user->setLastName($data ["lastName"]);

    
        //record the new user in the DB
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(["status" => "User created"], JsonResponse::HTTP_CREATED);
    }*/


}