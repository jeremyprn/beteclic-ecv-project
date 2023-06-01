<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // Création d'un utilisateur
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setAge(30);
        $user->setPicture('profile_picture.jpg');
        $user->setRoles(['ROLE_USER']);

        // Générer un mot de passe sécurisé
        $password = $this->passwordHasher->hashPassword($user, 'password123');
        $user->setPassword($password);

        // Persistez l'utilisateur dans le gestionnaire d'entités
        $manager->persist($user);

        // Effectuez les opérations de base de données
        $manager->flush();

         // Enregistrez la référence à l'utilisateur
        $this->addReference('default_user', $user);
    }
}
