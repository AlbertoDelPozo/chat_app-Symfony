<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(ManagerRegistry $doctrine, Request $request, FileUploader $fileUploader): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $user->setTlf($_POST['changeTlf']);

            /** @var UploadedFile $brochureFile */
            $brochureFile = $_FILES["fileToUpload"]["name"];
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($_FILES['fileToUpload']['name']);
                $user->setProfileImage($brochureFileName);
            }
        }
        //Save changes in database
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('profile');



        return $this->render('profile/index.html.twig', [
            'image' => $user->getProfileImage(),
        ]);
    }
}
