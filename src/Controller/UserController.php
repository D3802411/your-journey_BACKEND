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
use App\Form\UserType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/profile', name: 'app_user_profile')] 
    public function showProfile(): Response
    {       $user = $this->getUser();
            if(!$user) {
                $this->addFlash('error', 'Vous devez être');
                return $this->redirectToRoute('app_login');
            }
    return $this->render('user/profile.html.twig');
    }

    #[Route('/edit/{id}', name: 'app_user_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER', 'ROLE_ADMIN')] // Ensure only admin and logged-in users access this
    public function editUser(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
             // Make sure the logged-in user can only edit their own profile
        if ($user !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only edit your own profile.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
        ]);
    } 

    #[Route('/{id}', name: 'app_user_delete', requirements: ['id' => '\d+'])] 
    public function delete(User $user, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        //see if the user is registered (its own token) and may delete his account
                /** @var User|null $user */
            $user = $this->getUser();

            if (!$user) {
            return new JsonResponse(['error' => 'User not logged in.'], JsonResponse::HTTP_UNAUTHORIZED);
            }
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
    return new JsonResponse (['Message' => 'Account deleted successfully!']);

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
    } */



}