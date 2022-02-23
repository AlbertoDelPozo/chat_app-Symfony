<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistance\ManagerRegistry;
use App\Repository\UserRepository;

class SendMessageMultipleController extends AbstractController
{
    #[Route('/send/message/multiple', name: 'send_message_multiple')]
    public function index(ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {

        $date = new \DateTime('@' . strtotime('now'));

        /** "var \App\Entity\User $user */
        $user = $this->getUser();
        $allUsers = $userRepository->findAll();

        $entityManager = $doctrine->getManager();

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['messageMultiple'])) {
            foreach ($_POST['messageMultiple'] as $participant) {
                $message = new Messages();
                // * Get id of the user from the email
                $reciver = $userRepository
                    ->findBy(
                        ['email' => $participant]
                    );
                $message->setReciver($reciver[0]->getId());
                $message->setMessage($_POST['message']);
                $message->setSender($user->getId());
                $message->setDate($date);
                $message->setIsRead(false);
                $entityManager->persist($message);
                $entityManager->flush();
            }
            return $this->redirectToRoute('messages_index');
        }
        return $this->render('send_message_multiple/index.html.twig', [
            'controller_name' => 'SendMessageMultipleController',
            'users' => $allUsers
        ]);
    }
}
