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

class SendMessageController extends AbstractController
{
    #[Route('/send/message', name: 'send_message')]
    public function index(MessageRepository $messageRepository, ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        
        $entityManager = $doctrine->getManager();
        $message = new Message();
        $date = new \DateTime('@' . strtotime('now'));
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $userReceiver = $userRepository
            ->findBy(
                ['email' => $_POST['username']]
            );
            
            if ($user->getId() == $userReceiver[0]->getId()) {
                return $this->redirectToRoute('inbox');
            }

            //DATA FROM FORM
            $message->setReciver($userReceiver[0]->getId());
            $message->setMessage($_POST['message']);
            // $message->setAttachFile($_POST['fileToUpload']);

            //Data that is default
            $message->setSender($user->getId());
            $message->setDate($date);
            $message->setIsRead(false);

            //Save new message in database
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('outbox');
        }

        return $this->render('send_message/index.html.twig', [
            'controller_name' => 'SendMessageController',
        ]);
    }
}
