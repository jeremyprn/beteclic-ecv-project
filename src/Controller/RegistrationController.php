<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'beteclic_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, Authenticator $authenticator, EntityManagerInterface $entityManager, Security $security): Response
    {
        if ($security->getUser()) {
            $this->addFlash('info', 'Vous êtes déjà connecté !');
            return new RedirectResponse($this->generateUrl('beteclic_home')); 
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'âge
            $dateOfBirth = $form->get('dateOfBirth')->getData();
            $now = new \DateTime();
            $interval = $now->diff($dateOfBirth);
            $age = $interval->y;

            if ($age < 18) {
                $this->addFlash('info', 'Vous devez avoir au moins 18 ans pour vous inscrire.');
                return new RedirectResponse($this->generateUrl('beteclic_register'));
            }

            $user->setAge($age);

            // Gestion de l'image
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('app.path.images'),
                    $fileName
                );
            } catch (FileException $exception) {
                $this->addFlash('info', 'Une erreur est survenue lors de l\'upload de votre image.');
                return new RedirectResponse($this->generateUrl('beteclic_register'));
            }

            $user->setPicture($fileName);

            // Gestion du mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Ajout dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // À la validation du formulaire, on connecte l'utilisateur
            $this->addFlash('success', 'Inscription réussie !');
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
