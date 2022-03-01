<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Message;
use App\Repository\UserRepository;
// use symfony\component\HttpFoundation\Request;

// Controller to send messages

class SendMessageController extends AbstractController
{
    #[Route('/send/message', name: 'send_message')]
    public function index(MessageRepository $messageRepository, ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {

        //we call the entity user to get the user that it is logged
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        // We create a new message
        $message = new Message();
        // We create a new date
        $date = new \DateTime('@' . strtotime('now'));
        
        // We make a POST in order to save the information in the database
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            // We get the user email we want to send the message
            $userReceiver = $userRepository
            ->findBy(
                ['email' => $_POST['username']]
            );
            
            // If the user logged is the same id of the sender we redirect the user to the inbox directly
            if ($user->getId() == $userReceiver[0]->getId()) {
                return $this->redirectToRoute('inbox');
            }

            // We get the information from the POST to send the message
            $message->setReciver($userReceiver[0]->getId());
            $message->setMessage($_POST['message']);
            
            // Information to introduce the default infromation to the database
            $message->setSender($user->getId());
            $message->setDate($date);
            $message->setIsRead(false);

            $entityManager->persist($message);
            $entityManager->flush();

            // We redirect to the outbox to know if the messages had been sent
            return $this->redirectToRoute('outbox');
        }

        return $this->render('send_message/index.html.twig', [
            'controller_name' => 'SendMessageController',
        ]);
    }
}
