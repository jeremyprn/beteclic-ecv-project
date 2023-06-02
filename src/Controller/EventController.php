<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Event;
use App\Entity\SelectionEvent;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
//    #[Route('/', name: 'app_event_index', methods: ['GET'])]
//    public function index(EventRepository $eventRepository): Response
//    {
//        return $this->render('event/index.html.twig', [
//            'events' => $eventRepository->findAll(),
//        ]);
//    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $user = $this->getUser();

        $event->setUserId($user);
        $event->setIsOpen(1);





        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);


            $firstSelectEvent = new SelectionEvent();
            $secondSelectEvent = new SelectionEvent();

            $firstChoice = $form->get('firstChoice')->getData();
            $firstOdd = $form->get('firstOdd')->getData();
            $secondChoice = $form->get('secondChoice')->getData();
            $secondOdd = $form->get('secondOdd')->getData();


            $firstSelectEvent->setLabel($firstChoice);
            $firstSelectEvent->setOdd($firstOdd);
            $firstSelectEvent->setEventId($event);

            $secondSelectEvent->setLabel($secondChoice);
            $secondSelectEvent->setOdd($secondOdd);
            $secondSelectEvent->setEventId($event);

            $entityManager->persist($firstSelectEvent);
            $entityManager->persist($secondSelectEvent);
            $entityManager->flush();

            return $this->redirectToRoute('beteclic_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('beteclic_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('beteclic_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/validate/{idAnswer}', name: 'app_event_validate', methods: ['GET'])]
    public function validate(Request $request, Event $event,  int $idAnswer, EntityManagerInterface $entityManager): Response
    {

        $event->setIsOpen(0);
        $idSelectionEvent = $entityManager->getRepository(SelectionEvent::class)->find($idAnswer);
        $event->setSelectionEventId($idSelectionEvent);


        $bets = $entityManager->getRepository(Bet::class)->findBy(['selectionEventId' => $idSelectionEvent]);
        foreach ($bets as $bet){
            $userId = $bet->getUserId()->getId();
            $userBeteCoin = $bet->getUserId()->getBeteCoin();

            $winnerUser = $entityManager->getRepository(User::class)->findBy(['id' => $userId]);
            $winnerUser[0]->setBeteCoin($userBeteCoin + $bet->getPotentialGain());
            $entityManager->persist($winnerUser[0]);
        }

        $entityManager->persist($event);
        $entityManager->flush();


        return $this->redirectToRoute('beteclic_home', [], Response::HTTP_SEE_OTHER);
    }

}
