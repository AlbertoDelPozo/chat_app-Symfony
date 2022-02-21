<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InboxController extends AbstractController
{
    #[Route('/inbox', name: 'inbox')]
    public function index(): Response
    {
        return $this->render('inbox/index.html.twig', [
            'controller_name' => 'InboxController',
        ]);
    }
        
}
