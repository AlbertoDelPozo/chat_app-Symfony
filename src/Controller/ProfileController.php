<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(ManagerRegistry $doctrine): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $user->setTlf($_POST['changeTlf']);
            if(isset($_POST['fileToUpload']) && $_POST['fileToUpload'] !== '') {
                $user->setProfileImage($_POST['fileToUpload']);
            } 

            //Save changes in database
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile');
        }
            

        return $this->render('profile/index.html.twig', [
            'image' => $user->getProfileImage(),
        ]);
    }
}
