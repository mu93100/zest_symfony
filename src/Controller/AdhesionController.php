<?php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;
// use App\Entity\User;
// use App\Entity\Adhesion;
// use App\Entity\Groupe;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\HttpFoundation\Request;   
// use App\Form\AdhesionFormType;
// final class AdhesionController extends AbstractController
// {
//     #[Route('/adhesion', name: 'app_adhesion')]
//     public function index(): Response
//     {
//         $adhesion = new Adhesion();
//         $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion);

//         $adhesionForm->handleRequest($request);

//         if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
//             $entityManager->persist($adhesion);
//             $entityManager->flush();
//             // redirect...
//         }
//         return $this->render('adhesion/index.html.twig', [
//             'adhesionForm' => $adhesionForm->createView(),
//         ]);
//     }
// }

namespace App\Controller;

use App\Entity\User;
use App\Entity\Groupe;
use App\Form\AdhesionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdhesionController extends AbstractController
{
    #[Route('/adhesion', name: 'app_adhesion')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
// Injection: Request contient la requête HTTP (GET/POST), 
// EntityManagerInterface sert à persister/flush les entités.
    {
// ⚡ Création d'un nouvel user/ objet qui sera hydraté par le formulaire.
        $user = new User();

        // ⚡ Création du formulaire basé sur AdhesionFormType
        $adhesionForm = $this->createForm(AdhesionFormType::class, $user);
        $adhesionForm->handleRequest($request);
        // $adhesion = new Adhesion();
        //         $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion);

        //         $adhesionForm->handleRequest($request);

        //         if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
        //             $entityManager->persist($adhesion);
        //             $entityManager->flush();
        //             // redirect...
        // ⚡ Traitement du formulaire
        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
            // 1️⃣ Gestion du champ "nouveauGroupe"
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            if ($nouveauNom) {
                $nouveauGroupe = new Groupe();
                $nouveauGroupe->setNom($nouveauNom);
                $entityManager->persist($nouveauGroupe);
                $user->setGroupe($nouveauGroupe);
            }

            // 2️⃣ Gestion du champ "isOpen" si l’utilisateur est référent
            $isOpen = $adhesionForm->has('isOpen') ? $adhesionForm->get('isOpen')->getData() : null;
            if ($isOpen !== null && $user->isReferent() && $user->getGroupe()) {
                $user->getGroupe()->setIsOpen($isOpen);
            }

            // ⚡ Sauvegarde en base
            $entityManager->persist($user);
            $entityManager->flush();

            // ⚡ Redirection ou message de confirmation
            $this->addFlash('success', 'Votre adhésion a bien été enregistrée !');
            return $this->redirectToRoute('app_adhesion');
        }

        // ⚡ Affichage du formulaire
        return $this->render('adhesion/index.html.twig', [
            'adhesionForm' => $adhesionForm->createView(),
        ]);
    }
}
