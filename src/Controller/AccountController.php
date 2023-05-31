<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'beteclic_account')]
    public function index(): Response
    {
        $user = $this->getUser();
    
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à votre compte.');
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('account/index.html.twig', [
            'user' => $user
        ]);
    }
}
