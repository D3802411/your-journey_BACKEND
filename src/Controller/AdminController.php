<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/page', name: 'app_admin_page')]
    public function index(): Response
    {   
        
        return $this->render('admin_page/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
