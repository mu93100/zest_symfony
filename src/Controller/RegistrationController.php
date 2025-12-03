<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Groupe;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{
    #[Route('/enregistrement', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie si l'email existe déjà
            if ($userRepository->existsByEmail($user->getEmail())) {
                return $this->redirectToRoute('app_register');
            }

            $plainPassword = $form->get('plainPassword')->getData();
            // hashage password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $nouveauGroupe = $form->get('nouveau_groupe')->getData();
            if ($nouveauGroupe) {
                $groupe = new Groupe();
                $groupe->setNom($nouveauGroupe);
                $entityManager->persist($groupe);
                $user->setGroupe($groupe);
            }

            if ($form->get('is_referent')->getData()) {
                $user->setIsReferent(true);

                // Si le champ is_open est présent et coché
                $isOpen = $form->has('is_open') ? $form->get('is_open')->getData() : false;
                if ($user->getGroupe()) {
                    $user->getGroupe()->setIsGroupeOpen($isOpen);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès !');

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}

