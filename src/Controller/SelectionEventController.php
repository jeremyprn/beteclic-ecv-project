<?php

namespace App\Controller;

use App\Entity\SelectionEvent;
use App\Form\SelectionEventType;
use App\Repository\SelectionEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/selection/event')]
class SelectionEventController extends AbstractController
{
    #[Route('/', name: 'app_selection_event_index', methods: ['GET'])]
    public function index(SelectionEventRepository $selectionEventRepository): Response
    {
        return $this->render('selection_event/index.html.twig', [
            'selection_events' => $selectionEventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_selection_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SelectionEventRepository $selectionEventRepository): Response
    {
        $selectionEvent = new SelectionEvent();
        $form = $this->createForm(SelectionEventType::class, $selectionEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectionEventRepository->save($selectionEvent, true);

            return $this->redirectToRoute('app_selection_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('selection_event/new.html.twig', [
            'selection_event' => $selectionEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_selection_event_show', methods: ['GET'])]
    public function show(SelectionEvent $selectionEvent): Response
    {
        return $this->render('selection_event/show.html.twig', [
            'selection_event' => $selectionEvent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_selection_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SelectionEvent $selectionEvent, SelectionEventRepository $selectionEventRepository): Response
    {
        $form = $this->createForm(SelectionEventType::class, $selectionEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectionEventRepository->save($selectionEvent, true);

            return $this->redirectToRoute('app_selection_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('selection_event/edit.html.twig', [
            'selection_event' => $selectionEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_selection_event_delete', methods: ['POST'])]
    public function delete(Request $request, SelectionEvent $selectionEvent, SelectionEventRepository $selectionEventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$selectionEvent->getId(), $request->request->get('_token'))) {
            $selectionEventRepository->remove($selectionEvent, true);
        }

        return $this->redirectToRoute('app_selection_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
