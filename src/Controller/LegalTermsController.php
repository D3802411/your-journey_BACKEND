<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalTermsController extends AbstractController
{
    #[Route('/legalterms', name: 'app_legal_terms')]
    public function index(): Response
    {
        return $this->render('legalterms/index.html.twig', [
            'controller_name' => 'LegalTermsController',
        ]);
    }
}
