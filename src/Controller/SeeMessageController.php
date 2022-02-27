<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;


class SeeMessageController extends AbstractController
{
    // #[Route('/see_message/{id}', name: 'see_message',methods: ['GET','HEAD'])]
    // public function index(MessageRepository $messageRepository, UserRepository $userRepository, Message $message, ManagerRegistry $doctrine): Response
    // {

    //     $entityManager = $doctrine->getManager();

    //     $messages = $messageRepository
    //         ->findBy(
    //             ['id' => $message->getId()]
    //         );

    //     $email =  $userRepository
    //         ->findBy(
    //             ['id' => $messages[0]->getSender()]
    //         );

    //     $updateMessage = $entityManager->getRepository(Message::class)->find($message->getId());

    //     $updateMessage->setIsRead(true);
    //     $entityManager->flush();

    //     return $this->render('see_message/index.html.twig', [
    //         'message' => $messages,
    //         'users' => $email[0]

    //     ]);
    // }
}
