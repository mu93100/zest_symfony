<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdhesionController extends AbstractController
{
    #[Route('/adhesion', name: 'app_adhesion')]
    public function index(): Response
    {
        return $this->render('adhesion/index.html.twig', [
            'controller_name' => 'AdhesionController',
        ]);
    }
}
// <?php
// // -----------------------------
// // I A
// namespace App\Controller;

// use App\Entity\User;
// use App\Entity\Groupe;
// use App\Form\AdhesionFormType;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// final class AdhesionController extends AbstractController
// {
//     #[Route('/adhesion', name: 'app_adhesion')]
//     public function index(Request $request, EntityManagerInterface $entityManager): Response
// // Injection: Request contient la requête HTTP (GET/POST), 
// // EntityManagerInterface sert à persister/flush les entités.
//     {
// // ⚡ Création d'un nouvel user/ objet qui sera hydraté par le formulaire.
//         $user = new User();

//         // ⚡ Création du formulaire basé sur AdhesionFormType
//         $form = $this->createForm(AdhesionFormType::class, $user);
//         $form->handleRequest($request);

//         // ⚡ Traitement du formulaire
//         if ($form->isSubmitted() && $form->isValid()) {
//             // 1️⃣ Gestion du champ "nouveau_groupe"
//             $nouveauNom = $form->get('nouveau_groupe')->getData();
//             if ($nouveauNom) {
//                 $nouveauGroupe = new Groupe();
//                 $nouveauGroupe->setNom($nouveauNom);
//                 $entityManager->persist($nouveauGroupe);
//                 $user->setGroupe($nouveauGroupe);
//             }

//             // 2️⃣ Gestion du champ "isOpen" si l’utilisateur est référent
//             $isOpen = $form->has('isOpen') ? $form->get('isOpen')->getData() : null;
//             if ($isOpen !== null && $user->isReferent() && $user->getGroupe()) {
//                 $user->getGroupe()->setIsOpen($isOpen);
//             }

//             // ⚡ Sauvegarde en base
//             $entityManager->persist($user);
//             $entityManager->flush();

//             // ⚡ Redirection ou message de confirmation
//             $this->addFlash('success', 'Votre adhésion a bien été enregistrée !');
//             return $this->redirectToRoute('app_adhesion');
//         }

//         // ⚡ Affichage du formulaire
//         return $this->render('adhesion/index.html.twig', [
//             'form' => $form->createView(),
//         ]);
//     }
// }
