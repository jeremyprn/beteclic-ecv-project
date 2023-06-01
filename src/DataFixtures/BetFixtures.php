<?php

namespace App\DataFixtures;

use App\Entity\Bet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BetFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Récupérer un utilisateur existant
        $user = $this->getReference('default_user');
        $selectionEvent1 = $this->getReference('default_selection_event_1');
        $selectionEvent2 = $this->getReference('default_selection_event_2');

        // Création d'un pari
        $bet = new Bet();
        // Définir les propriétés du pari
        $bet->setAmount(100.0);
        $bet->setOdd(2.5);
        $bet->setPotentialGain(250.0);
        $bet->setUserId($user);
        // Récupérer un événement de sélection existant
        $bet->setSelectionEventId($selectionEvent1); 
        $bet->setSelectionEventId($selectionEvent2);

        // Persistez les objets dans le gestionnaire d'entités
        $manager->persist($selectionEvent1);
        $manager->persist($selectionEvent2);
        $manager->persist($bet);

        // Effectuez les opérations de base de données
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            EventFixtures::class,
        ];
    }
}

