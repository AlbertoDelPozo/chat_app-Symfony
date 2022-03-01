<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;

//inbox controller to show all the messages that the user has recived

class InboxController extends AbstractController
{
    #[Route('/inbox', name: 'inbox')]
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {

        //we call the entity user to get the user that it is logged
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        //we get all the users in order to know who has sent us the email
        $allUsers = $userRepository->findAll();

        //Query to get the messages received
        $messages = $messageRepository->createQueryBuilder('u')
            ->andWhere('u.reciver = :val')
            ->setParameter('val', $user->getId())
            ->orderBy('u.date', 'DESC')
            ->getQuery()
            ->getResult();

        //we render the information to print it on the twig template
        return $this->render('inbox/index.html.twig', [
            'inbox_message' => $messages,
            'users' => $allUsers,
            'user_role' => $user->getRoles()[0]
        ]);
    }

    #[Route('/see_message/{id}', name: 'see_message',methods: ['GET','HEAD'])]
    public function seeMenssage(MessageRepository $messageRepository, UserRepository $userRepository, Message $message, ManagerRegistry $doctrine): Response
    {
        //Controller to see in detail the message
        //We get the id of the message
        $entityManager = $doctrine->getManager();
        $messages = $messageRepository
            ->findBy(
                ['id' => $message->getId()]
            );

        //Also we get the id of the sender
        $email =  $userRepository
            ->findBy(
                ['id' => $messages[0]->getSender()]
            );
        
        //We update the isRead
        $updateMessage = $entityManager->getRepository(Message::class)->find($message->getId());
        $updateMessage->setIsRead(true);
        $entityManager->flush();

        // We render the information needed in the twig
        return $this->render('see_message/index.html.twig', [
            'message' => $messages,
            'users' => $email[0]

        ]);
    }
}
