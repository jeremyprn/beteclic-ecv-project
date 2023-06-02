<?php

namespace App\Controller;

use App\Entity\Event;
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

        $allBets = $this->entityManager
        ->getRepository(Bet::class)
        ->findAll();


        $ownBets = $this->entityManager
        ->getRepository(Bet::class)
        ->findBy(['userId' => $user->getId()]);

        $ownEvents = $this->entityManager
            ->getRepository(Event::class)
            ->findBy(['userId' => $user->getId(), 'isOpen' => true]);

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'allBets' => $allBets,
            'ownBets' => $ownBets,
            'ownEvents' => $ownEvents
        ]);
    }
}
