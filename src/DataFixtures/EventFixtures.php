<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Event;
use App\Entity\Category;
use App\Entity\SelectionEvent;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('default_user');

        // Création de catégories
        $category1 = new Category();
        $category1->setName('Category 1');

        $category2 = new Category();
        $category2->setName('Category 2');

        // Création des événements de sélection
        $selectionEvent1 = new SelectionEvent();
        $selectionEvent1->setLabel('Yes');
        $selectionEvent1->setOdd(1.5);
        $this->addReference('default_selection_event_1', $selectionEvent1);

        $selectionEvent2 = new SelectionEvent();
        $selectionEvent2->setLabel('No');
        $selectionEvent2->setOdd(2.5);
        $this->addReference('default_selection_event_2', $selectionEvent2);

        // Persistez les catégories dans le gestionnaire d'entités
        $manager->persist($category1);
        $manager->persist($category2);

        // Création d'un événement
        $event = new Event();
        $event->setTitle('Event 1');
        $event->setDescription('Description of Event 1');
        $event->setDate(new \DateTime());
        $event->setIsOpen(true);
        $event->setUserId($user);
        $event->setSelectionEventId($selectionEvent1);
        $event->setSelectionEventId($selectionEvent2);

        // Ajout des catégories à l'événement
        $event->addCategory($category1);
        $event->addCategory($category2);

        // Persistez l'événement dans le gestionnaire d'entités
        $manager->persist($event);

        // Effectuez les opérations de base de données
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
