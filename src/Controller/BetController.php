<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Form\BetType;
use App\Repository\BetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bet')]
class BetController extends AbstractController
{
    #[Route('/event/{eventId}/selection-event/{selectionEventId}/new', name: 'app_bet_new', methods: ['GET', 'POST'])]
    public function newBet(Request $request, int $eventId, int $selectionEventId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $event = $entityManager->getRepository(Event::class)->find($eventId);
        $selectionEvent = $entityManager->getRepository(SelectionEvent::class)->find($selectionEventId);

        if (!$event || !$selectionEvent) {
            throw $this->createNotFoundException('Event or SelectionEvent not found');
        }

        $bet = new Bet();
        // $bet->setEvent($event);
        // $bet->setSelectionEvent($selectionEvent);

        // vous devez passer le User à votre Bet. Je suppose que vous avez un système d'authentification.
        // $bet->setUser($this->getUser());

        $form = $this->createForm(BetType::class, $bet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bet);
            $entityManager->flush();

            return $this->redirectToRoute('bet_success');
        }

        return $this->render('bet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
