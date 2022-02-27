<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;

class InboxController extends AbstractController
{
    #[Route('/inbox', name: 'inbox')]
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $allUsers = $userRepository->findAll();

        $messages = $messageRepository->createQueryBuilder('u')
            ->andWhere('u.sender = :val')
            ->setParameter('val', $user->getId())
            ->orderBy('u.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('inbox/index.html.twig', [
            'inbox_message' => $messages,
            'users' => $allUsers
        ]);
    }

    #[Route('/see_message/{id}', name: 'see_message',methods: ['GET','HEAD'])]
    public function seeMenssage(MessageRepository $messageRepository, UserRepository $userRepository, Message $message, ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();

        $messages = $messageRepository
            ->findBy(
                ['id' => $message->getId()]
            );

        $email =  $userRepository
            ->findBy(
                ['id' => $messages[0]->getSender()]
            );

        $updateMessage = $entityManager->getRepository(Message::class)->find($message->getId());

        $updateMessage->setIsRead(true);
        $entityManager->flush();

        return $this->render('see_message/index.html.twig', [
            'message' => $messages,
            'users' => $email[0]

        ]);
    }
}
