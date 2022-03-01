<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use App\Entity\Message;
use App\Repository\MessageRepository;

// Controller to send a message to multiple users

class SendMessageMultipleController extends AbstractController
{
    #[Route('/send/message/multiple', name: 'send_message_multiple')]
    public function index(ManagerRegistry $doctrine, UserRepository $userRepository, MessageRepository $messageRepository): Response
    {

        // We call to the object date
        $date = new \DateTime('@' . strtotime('now'));

        //we call the entity user to get the user that it is logged
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        // We get all the users in order to know who has sent the email
        $allUsers = $userRepository->findAll();
        $entityManager = $doctrine->getManager();

        // Post to save all the information into the database
        // If is isset messageMultiple we sent to the users selected the massage created
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['messageMultiple'])) {

            // For each user selected we sent one message
            foreach ($_POST['messageMultiple'] as $participant) {

                // We create a message
                $message = new Message();
                
                // We get the email of the receiver by their id
                $reciver = $userRepository
                    ->findBy(
                        ['email' => $participant]
                    );

                // We set the information from the POST
                $message->setReciver($reciver[0]->getId());
                $message->setMessage($_POST['message']);
                $message->setSender($user->getId());
                $message->setDate($date);
                $message->setIsRead(false);

                // We save that information in the database
                $entityManager->persist($message);
                $entityManager->flush();
            }

            // We redirect to the outbox to see if all the messages had been sent correctly
            return $this->redirectToRoute('outbox');
        }
        
        // We render the information needed for the twig
        return $this->render('send_message_multiple/index.html.twig', [
            'controller_name' => 'SendMessageMultipleController',
            'users' => $allUsers
        ]);
    }
}
