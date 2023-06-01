<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'beteclic_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Category::class)
            ->findAll();


        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();

        $user = $this->getUser();


        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'events' => $events,
            'user' => $user,
        ]);
    }
}
