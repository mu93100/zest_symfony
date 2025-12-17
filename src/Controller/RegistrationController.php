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
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie si l'email existe déjà
            if ($userRepository->existsByEmail($user->getEmail())) {
                $this->addFlash('error', 'E R R O R  cet email est déjà utilisé');
                return $this->redirectToRoute('app_register');
            }

            // GESTION DU GROUPE
            $nouveauNomGroupe = $form->get('nouveauGroupe')->getData();
            $groupeSelectionne = $form->get('groupe')->getData();

            if ($nouveauNomGroupe) {
                // Créer un nouveau groupe
                $groupe = new Groupe();
                $groupe->setNom($nouveauNomGroupe);
                $groupe->setVille($user->getVille()); // Ville du référent
                $entityManager->persist($groupe);
                $user->setGroupe($groupe);
            } elseif ($groupeSelectionne) {
                // Utiliser le groupe sélectionné
                $user->setGroupe($groupeSelectionne);
            } 

            // IsOpen sur le groupe
            $isOpen = $form->has('isOpen') ? $form->get('isOpen')->getData() : false;
            if ($user->getGroupe()) {
                $user->getGroupe()->setIsOpen($isOpen);
            }

            // Mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $plainPassword)
            );

            // Persistance user
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', '[ ton compte a été créé avec succès ] !');

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
