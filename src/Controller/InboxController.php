<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 

class InboxController extends AbstractController
{
    #[Route('/inbox', name: 'inbox')]
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $messages = $messageRepository
            ->findBy(
                ['reciver' => $user->getId()]
            );
            

        return $this->render('inbox/index.html.twig', [
            'inbox_message' => $messages
        ]);
    }
}
