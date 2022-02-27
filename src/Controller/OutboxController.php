<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutboxController extends AbstractController
{
    #[Route('/outbox', name: 'outbox')]
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $allUsers = $userRepository->findAll();

        $messages = $messageRepository->createQueryBuilder('u')
            ->andWhere('u.reciver = :val')
            ->setParameter('val', $user->getId())
            ->orderBy('u.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('outbox/index.html.twig', [
            'outbox_message' => $messages,
            'users' => $allUsers
        ]);
    }
}
