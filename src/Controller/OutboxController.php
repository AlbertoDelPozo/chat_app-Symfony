<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//outbox controller to show all the messages that the user has sent

class OutboxController extends AbstractController
{
    #[Route('/outbox', name: 'outbox')]
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {

        //we call the entity user to get the user that it is logged
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        //we get all the users in order to know who has sent us the email
        $allUsers = $userRepository->findAll();

        //Query to get the messages sent
        $messages = $messageRepository->createQueryBuilder('u')
            ->andWhere('u.sender = :val')
            ->setParameter('val', $user->getId())
            ->orderBy('u.date', 'DESC')
            ->getQuery()
            ->getResult();

        //we render the information to print it on the twig template
        return $this->render('outbox/index.html.twig', [
            'outbox_message' => $messages,
            'users' => $allUsers
        ]);
    }
}
