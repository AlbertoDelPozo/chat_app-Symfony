<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;

// Profile controller to see the information of the user and to change it

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(ManagerRegistry $doctrine): Response
    {
        //we call the entity user to get the user that it is logged
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        // Post to change the user information
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // We change the information from the POST
            $user->setTlf($_POST['changeTlf']);
            $user->setStreet($_POST['street']);
 
            // We put the information into the database
            $entityManager->persist($user);
            $entityManager->flush();
    
            // We redirect again to the profile page
            return $this->redirectToRoute('profile');
        }
   
        return $this->render('profile/index.html.twig', [
        ]);
    }
}
