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
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('[ Tu dois être connecté pour adhérer ]');
        }

        // Créer une nouvelle adhésion
        $adhesion = new Adhesion();

        // Saison en cours
        $saisonEnCours = $saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);
        if ($saisonEnCours) {
            $adhesion->setSaison($saisonEnCours);
        }

        // Créer le formulaire
        $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion, [
            'user' => $user,
        ]);
        $adhesionForm->handleRequest($request);
        
        // dd($adhesionForm); // dump complet de l'objet Form
        // dd($adhesionForm->getData());              // données mappées sur l'entité
        // dd($adhesionForm->all());                  // tous les champs
        // dd($adhesionForm->createView()->vars);     // ce qui part vers Twig

        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
            // --- Création d’un nouveau groupe ---
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            // --- Création d’un nouveau groupe ---
            if ($nouveauNom) {
                $nouveauGroupe = new Groupe();
                $nouveauGroupe->setNom($nouveauNom);
                $nouveauGroupe->setAdresseDistrib($adhesionForm->get('adresseDistrib')->getData());
                $nouveauGroupe->setVille($adhesionForm->get('ville')->getData());
                $nouveauGroupe->setIsOpen((bool) $adhesionForm->get('isOpen')->getData());

                $entityManager->persist($nouveauGroupe);

                $adhesion->setGroupe($nouveauGroupe);
                $user->setGroupe($nouveauGroupe);

                // --- Changement de groupe existant ---
            } elseif ($adhesionForm->get('changeGroupe')->getData()) {
                /** @var Groupe $groupeChoisi */
                $groupeChoisi = $adhesionForm->get('changeGroupe')->getData();

                $nouvelleAdresse = $adhesionForm->get('adresseDistrib')->getData();
                $nouvelleVille   = $adhesionForm->get('ville')->getData();

                if ($nouvelleAdresse !== null && $nouvelleAdresse !== '') {
                    $groupeChoisi->setAdresseDistrib($nouvelleAdresse);
                }
                if ($nouvelleVille !== null && $nouvelleVille !== '') {
                    $groupeChoisi->setVille($nouvelleVille);
                }

                $groupeChoisi->setIsOpen((bool) $adhesionForm->get('isOpen')->getData());

                $adhesion->setGroupe($groupeChoisi);
                $user->setGroupe($groupeChoisi);

                // --- Sinon garder le groupe actuel ---
            } else {
                /** @var Groupe|null $groupeActuel */
                $groupeActuel = $user->getGroupe();
                if (!$groupeActuel) {
                    throw new \LogicException('Le user doit avoir un groupe pour adhérer.');
                }

                $nouvelleAdresse = $adhesionForm->get('adresseDistrib')->getData();
                $nouvelleVille   = $adhesionForm->get('ville')->getData();

                if ($nouvelleAdresse !== null && $nouvelleAdresse !== '') {
                    $groupeActuel->setAdresseDistrib($nouvelleAdresse);
                }
                if ($nouvelleVille !== null && $nouvelleVille !== '') {
                    $groupeActuel->setVille($nouvelleVille);
                }

                $groupeActuel->setIsOpen((bool) $adhesionForm->get('isOpen')->getData());

                $adhesion->setGroupe($groupeActuel);
            }


            // --- Flag référent ---
            $user->setIsReferent((bool) $adhesionForm->get('isReferent')->getData());

            // --- Lier l’adhésion au user ---
            $adhesion->setUser($user);

            // Filet de sécurité final
            if (!$adhesion->getGroupe()) {
                $adhesion->setGroupe($user->getGroupe());
                if (!$adhesion->getGroupe()) {
                    throw new \LogicException('Aucun groupe sur l’adhésion ni sur le user.');
                }
            }


            // --- Sauvegarde en base ---
            $entityManager->persist($adhesion);
            $entityManager->persist($user);
            $entityManager->flush();

            // --- Flash recap ---
            $this->addFlash('success', $this->renderView('adhesion/_recap.html.twig', [
                'user' => $user,
                'groupe' => $adhesion->getGroupe(),
                'saison' => $adhesion->getSaison(),
                'motivations' => $adhesion->getMotivations(),
                'montantAdhesion' => $adhesion->getMontantAdhesion(),
            ]));

            // Redirection pour éviter de rester sur le POST
            return $this->redirectToRoute('app_accueil');
        }

        // 👉 Retour si formulaire non soumis ou invalide
        return $this->render('adhesion/index.html.twig', [
            'adhesionForm' => $adhesionForm->createView(),
            'saison' => $saisonEnCours,
            'user' => $user,
            'montantAdhesion' => $adhesion->getMontantAdhesion(),
        ]);
    }
}
