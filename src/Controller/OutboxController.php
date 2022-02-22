<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutboxController extends AbstractController
{
    #[Route('/outbox', name: 'outbox')]
    public function index(MessageRepository $messageRepository): Response
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $messages = $messageRepository ->findBy(
            ['sender' => $user->getId()]
        );

        return $this->render('outbox/index.html.twig', [
            'outbox_message' => $messages
        ]);
    }
}
