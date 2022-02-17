<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutboxController extends AbstractController
{
    #[Route('/outbox', name: 'outbox')]
    public function index(): Response
    {
        return $this->render('outbox/index.html.twig', [
            'controller_name' => 'OutboxController',
        ]);
    }
}
