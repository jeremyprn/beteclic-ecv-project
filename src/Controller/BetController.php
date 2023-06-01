<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Event;
use App\Entity\SelectionEvent;
use App\Form\BetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bet')]
class BetController extends AbstractController
{
    #[Route('/event/{eventId}/selection-event/{selectionEventId}/create', name: 'app_bet_new', methods: ['GET', 'POST'], requirements: ['eventId' => '\d+', 'selectionEventId' => '\d+'])]
    public function newBet(Request $request, int $eventId, int $selectionEventId, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($eventId);
        $selectionEvent = $entityManager->getRepository(SelectionEvent::class)->find($selectionEventId);
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour pouvoir parier.');
            return $this->redirectToRoute('app_login');
        }

        if (!$event || !$selectionEvent) {
            return $this->redirectToRoute('beteclic_home');
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

            if ($amount < 0 || $amount == 0 || !is_numeric($amount)) {
                return $this->render('bet/create.html.twig', [
                    'form' => $form->createView(),
                    'error' => 'Vous ne pouvez pas parier une somme négative ou égale à zéro.',
                    'user' => $user,
                    'event' => $event,
                    'selectionEvent' => $selectionEvent,
                ]);
            }

            $potentialGain = $amount * $bet->getOdd();
            $bet->setPotentialGain($potentialGain);

            if ($user->getBetecoin() < $amount) {
                return $this->render('bet/create.html.twig', [
                    'form' => $form->createView(),
                    'error' => 'Vous n\'avez pas assez de Betecoins pour ce pari.',
                    'user' => $user,
                    'event' => $event,
                    'selectionEvent' => $selectionEvent,
                ]);
            }

            $user->setBetecoin($user->getBetecoin() - $amount);

            $entityManager->persist($bet);
            $entityManager->flush();
            return $this->redirectToRoute('beteclic_account');
        }

        return $this->render('bet/create.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'event' => $event,
            'selectionEvent' => $selectionEvent,
            "error" => ""
        ]);
    }
}
