<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset/password/request', name: 'app_reset_password_request')]
    public function requestpw(Request $request): Response
    {   
        //handle the request form
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            // do I need this since the handlerequest already handles the user email address?
            //$email = $form->getEmail->getData();
            //$user = $entityManager->getRepository(User::class)->findOneBy('email'); 
            
            // once the email address is ok, the token is generated at the back and it will be displayed on its page
            if ($user) {
            $resetToken = bin2hex(random_bytes(32));  //method to generate a secure token
            $user->setResetToken($resetToken);
            $user->setResetTokenExpiresAt(new \DateTime('+1 hour'));

            $entityManager->persist();
            $entityManager->flush();
             // Display the token to the user
             return $this->redirectToRoute('reset_password/token.html.twig', [
                'token' => $resetToken,      
                ]);
            }

        }
      
    return $this->render('reset_password/token.html.twig', [
        'resetPasswordRequestForm' => $form->createView(),
    ]);      
    }

    #[Route('/reset/password/reset', name: 'app_reset_password_reset')]
    public function resetConfirm(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $token = $form->get('token')->getData();
            $newPassword = $form->get('plainPassword')->getData();
    
            $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
    
            if (!$user || $user->getResetTokenExpiresAt() < new \DateTime()) {
                $this->addFlash('danger', 'Invalid or expired token.');
                return $this->redirectToRoute('app_reset_password_confirm');
            }
    
            // Update the password
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);
    
            $entityManager->flush();
    
            $this->addFlash('success', 'Your password has been successfully reset.');
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('reset_password/reset_confirm.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}

