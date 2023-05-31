<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bet; 

class AccountController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/account', name: 'beteclic_account')]
    public function index(): Response
    {
        $user = $this->getUser();
    
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à votre compte.');
            return $this->redirectToRoute('app_login');
        }

        $bets = $this->entityManager
        ->getRepository(Bet::class)
        ->findAll();

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'bets' => $bets
        ]);
    }
}
