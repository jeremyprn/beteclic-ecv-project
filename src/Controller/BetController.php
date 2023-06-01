<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Event;
use App\Entity\SelectionEvent;
use App\Form\BetType;
use App\Repository\BetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bet')]
class BetController extends AbstractController
{
    #[Route('/event/{eventId}/selection-event/{selectionEventId}/create', name: 'app_bet_new', methods: ['GET', 'POST'])]
    public function newBet(Request $request, int $eventId, int $selectionEventId, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($eventId);
        $selectionEvent = $entityManager->getRepository(SelectionEvent::class)->find($selectionEventId);

        if (!$event || !$selectionEvent) {
            throw $this->createNotFoundException('Event or SelectionEvent not found');
        }

        $bet = new Bet();
        $bet->setSelectionEventId($selectionEvent);
        $bet->setUserId($this->getUser());
        $bet->setOdd($selectionEvent->getOdd());

        $form = $this->createForm(BetType::class, $bet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $betForm = $form->getData();
            $amount = $betForm->getAmount();
            $potentialGain = $amount * $bet->getOdd();
            $bet->setPotentialGain($potentialGain);

            $entityManager->persist($bet);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('bet/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
