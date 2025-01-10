<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {   
        $data = new ContactDTO();
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isvalid()) 
        {
            $email = (new TemplatedEmail())
            ->to('contact@yourjourney.org')
            ->from($data->email)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Contact request')
            ->text('Sending emails is fun again!')
            ->htmlTemplate("emails/contact.html.twig")
            ->context(["data" => $data]);

        $mailer->send($email);
        $this->addFlash("success", "Your message is sent");
        $this->redirectToRoute("app_contact");
        }


        return $this->render("contact/contact.html.twig", [
            'form' => $form,
        ]);
    }
}
