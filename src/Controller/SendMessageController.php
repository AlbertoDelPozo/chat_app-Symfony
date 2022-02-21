<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;

class SendMessageController extends AbstractController
{
    #[Route('/send/message', name: 'send_message')]
    public function index(MessageRepository $messageRepository): Response
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        }
    


        return $this->render('send_message/index.html.twig', [
            'controller_name' => 'SendMessageController',
        ]);
        

    
    }





}
