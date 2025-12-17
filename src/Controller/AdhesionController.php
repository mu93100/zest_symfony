<?php

namespace App\Controller;

use App\Entity\Adhesion;
use App\Entity\Groupe;
use App\Entity\User;
use App\Form\AdhesionFormType;
use App\Repository\SaisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdhesionController extends AbstractController
{
    #[Route('/adhesion', name: 'app_adhesion')]
    public function index(Request $request, EntityManagerInterface $entityManager, SaisonRepository $saisonRepository): Response
    {
        $adhesion = new Adhesion();

        // Saison en cours
        $saisonEnCours = $saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);
        if ($saisonEnCours) {
            $adhesion->setSaison($saisonEnCours);
        }

        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Tu dois être connecté pour adhérer.');
        }

        $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion, [
            'user' => $user,
        ]);
        $adhesionForm->handleRequest($request);

        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
             // Création d’un nouveau groupe
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            if ($nouveauNom) {
                $nouveauGroupe = new Groupe();
                $nouveauGroupe->setNom($nouveauNom);
                $nouveauGroupe->setAdresseDistrib($adhesionForm->get('adresseDistribution')->getData());
                $nouveauGroupe->setVille($adhesionForm->get('ville')->getData());
                $nouveauGroupe->setIsOpen($adhesionForm->get('isOpen')->getData());
            
                $entityManager->persist($nouveauGroupe);
            
                $adhesion->setGroupe($nouveauGroupe);
                $user->setGroupe($nouveauGroupe);
            
                if ($adhesionForm->get('isReferent')->getData()) {
                    $user->setIsReferent(true);
                }
            
            // Changement de groupe
            } elseif ($adhesionForm->get('changeGroupe')->getData()) {
                $groupeChoisi = $adhesionForm->get('changeGroupe')->getData();
                $adhesion->setGroupe($groupeChoisi);
                $user->setGroupe($groupeChoisi);
            
            // Sinon, garder le groupe actuel
            } else {
                $adhesion->setGroupe($user->getGroupe());
            }
            

            // Lier l’adhésion au user
            $adhesion->setUser($user);

            // Sauvegarde en base
            $entityManager->persist($adhesion);
            $entityManager->persist($user); // important pour enregistrer le changement de groupe et le flag référent
            $entityManager->flush();

            $this->addFlash('success', 'Adhésion enregistrée ! Merci de régler par virement sous 8 jours.');
            return $this->redirectToRoute('app_adhesion');
        }

        return $this->render('adhesion/index.html.twig', [
            'adhesionForm' => $adhesionForm->createView(),
            'saison' => $saisonEnCours,
            'user' => $user,
        ]);
    }
}

// namespace App\Controller;

// use App\Entity\Adhesion;
// use App\Entity\Groupe;
// use App\Entity\User;
// use App\Form\AdhesionFormType;
// use App\Repository\SaisonRepository;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;




// final class AdhesionController extends AbstractController
// {
//     #[Route('/adhesion', name: 'app_adhesion')]
//     public function index(Request $request, EntityManagerInterface $entityManager, SaisonRepository $saisonRepository): Response
//     // Injection: Request contient la requête HTTP (GET/POST), 
//     // EntityManagerInterface sert à persister/flush les entités.

//     { // ⚡ Création d'une nouvelle adhesion/ objet qui sera hydraté par le formulaire.
//         $adhesion = new Adhesion();

//         // recup saison en cours 
//         $saisonEnCours = $saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);
//         if ($saisonEnCours) {
//             $adhesion->setSaison($saisonEnCours);
//         }

//         // création du formulaire 
//         $user = $this->getUser(); // on récup le user connecté
//         if (!$user) {
//             throw $this->createAccessDeniedException('Tu dois être connecté pour adhérer.');
//         }

//         $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion, [
//             'user' => $user,
//         ]);
//         $adhesionForm->handleRequest($request);

//         if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
//             // Création d’un nouveau groupe
//             $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
//             if ($nouveauNom) {
//                 $nouveauGroupe = new Groupe();
//                 $nouveauGroupe->setNom($nouveauNom);
//                 $nouveauGroupe->setAdresseDistrib($adhesionForm->get('adresseDistribution')->getData());
//                 $nouveauGroupe->setVille($adhesionForm->get('ville')->getData());
//                 $nouveauGroupe->setIsOpen($adhesionForm->get('isOpen')->getData());

//                 $entityManager->persist($nouveauGroupe);
//                 $adhesion->setGroupe($nouveauGroupe);
//                 $user->setGroupe($nouveauGroupe);
//                 $user->setIsReferent(true);

//                 // Changement de groupe
//             } elseif ($adhesionForm->get('changeGroupe')->getData()) {
//                 $groupeChoisi = $adhesionForm->get('changeGroupe')->getData();
//                 $adhesion->setGroupe($groupeChoisi);
//                 $user->setGroupe($groupeChoisi);

//                 // Sinon, garder le groupe actuel
//             } else {
//                 $adhesion->setGroupe($user->getGroupe());
//             }

//             // Lier l’adhésion au user
//             $adhesion->setUser($user);

//             $entityManager->persist($adhesion);
//             $entityManager->flush();

//             $this->addFlash('success', 'Adhésion enregistrée ! Merci de régler par virement sous 8 jours.');
//             return $this->redirectToRoute('app_adhesion');
//         }

//         return $this->render('adhesion/index.html.twig', [
//             'adhesionForm' => $adhesionForm->createView(),
//             'saison' => $saisonEnCours,
//             'user' => $user,
//         ]);
//     }
// }
//         


// validation des données du formulaire
//         if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
//             $adhesion = $adhesionForm->getData();
//             $user = $this->getUser();

// if (!$user) {
//     throw $this->createAccessDeniedException('Tu dois être connecté pour adhérer.');
// }

//             // // dd : pour debug/ voir les donnees après submit   
//             // dd($adhesion);
            

//             // 1️⃣ Création d’un nouveau groupe si un nom est saisi
//             $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
//             if ($nouveauNom) {
//                 $nouveauGroupe = new Groupe();
//                 $nouveauGroupe->setNom($nouveauNom);
//                 // optionnel : remplir adresse / ville à partir des champs non mappés
//                 $nouveauGroupe->setadresseDistrib($adhesionForm->get('adresseDistribution')->getData());
//                 $nouveauGroupe->setVille($adhesionForm->get('ville')->getData());

//                 $entityManager->persist($nouveauGroupe);
//                 $adhesion->setGroupe($nouveauGroupe);

//                 if ($user) {
//                     $user->setGroupe($nouveauGroupe);
//                     $user->setIsReferent(true);
//                 }

//                 // 2️⃣ Sinon, si "Je change de groupe" est coché, on prend le groupe choisi
//             } elseif ($adhesionForm->get('changeGroupe')->getData()) {
//                 $groupeChoisi = $adhesionForm->get('groupe')->getData();
//                 if ($groupeChoisi) {
//                     $adhesion->setGroupe($groupeChoisi);
//                     if ($user) {
//                         $user->setGroupe($groupeChoisi);
//                     }
//                 }

//                 // 3️⃣ Sinon, on reste sur le groupe actuel du user
//             } else {
//                 if ($user && $user->getGroupe()) {
//                     $adhesion->setGroupe($user->getGroupe());
//                 }
//             }

//             // Lier l’adhésion au user connecté
//             if ($user) {
//                 $adhesion->setUser($user);
//             }

//             // ⚡ Sauvegarde en base
//             $entityManager->persist($adhesion);
//             $entityManager->flush();

//             // ⚡ Redirection ou message de confirmation
//             $this->addFlash('success', 'A D H E S I O N    E N R E G I S T R E E   [ merci de la régler par virement sous 8 jours à Corto-Zest IBAN FR 0000 0000 0000 0000 000 ]');
//             return $this->redirectToRoute('app_adhesion');

            
//         }

//         // ⚡ Affichage du formulaire
//         return $this->render('adhesion/index.html.twig', [
//             'adhesionForm' => $adhesionForm->createView(),
//             'saison' => $saisonEnCours,
//             'user' => $this->getUser(),
//         ]);
//     }

// }
