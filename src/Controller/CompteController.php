<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CompteFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;    
use App\Entity\User;    
use App\Entity\Groupe;      
        


final class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        $user = $this->getUser();

        $form = $this->createForm(CompteFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion du nouveau groupe
            $nouveau = $form->get('nouveauGroupe')->getData();
            if ($nouveau) {
                $g = new Groupe();
                $g->setNom($nouveau);
                $g->setVille($user->getVille());
                $em->persist($g);
                $user->setGroupe($g);
            }

            // Nouveau mot de passe
            $newPassword = $form->get('newPassword')->getData();
            if ($newPassword) {
                $user->setPassword(
                    $hasher->hashPassword($user, $newPassword)
                );
            }

            $em->flush();

            $this->addFlash('success', 'Vos informations ont été mises à jour.');
            return $this->redirectToRoute('app_compte');
        }

        return $this->render('compte/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
    // #[Route('/compte', name: 'app_compte')] 
    // public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    // {
    //     $user = $this->getUser();
    //     $form = $this->createForm(CompteFormType::class, $user);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) { // Gestion du changement de mot de passe $newPassword = $form->get('newPassword')->getData(); if ($newPassword) { $user->setPassword( $hasher->hashPassword($user, $newPassword) ); } $em->flush(); $this->addFlash('success', 'Vos informations ont été mises à jour'); return $this->redirectToRoute('app_compte'); 
    //         } 
    //         return $this->render('compte/index.html.twig', [ 'form' => $form->createView(), ]); } 
    //     }
