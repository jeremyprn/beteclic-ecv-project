<?php

namespace App\Controller;

use App\Entity\Bet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'beteclic_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Un exemple d'appel à la base de données
        // Si on veut faire des requêtes personnalisées, c'est possible, dans le repository
        $bets = $entityManager->getRepository(Bet::class)->findAll();

        if (!$bets) {
            throw $this->createNotFoundException(
                'No bets found'
            );
        }
        
        return $this->render('home/index.html.twig', [
            'bets' => $bets
        ]);
    }
}
