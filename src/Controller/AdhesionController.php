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

        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
            // --- Création d’un nouveau groupe ---
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            if ($nouveauNom) {
                $nouveauGroupe = new Groupe();
                $nouveauGroupe->setNom($nouveauNom);
                $nouveauGroupe->setAdresseDistrib($adhesionForm->get('adresseDistribNouveau')->getData());
                $nouveauGroupe->setVille($adhesionForm->get('villeNouveau')->getData());
                $nouveauGroupe->setIsOpen((bool) $adhesionForm->get('isOpen')->getData());

                $entityManager->persist($nouveauGroupe);

                $adhesion->setGroupe($nouveauGroupe);
                $user->setGroupe($nouveauGroupe);

            // --- Changement de groupe existant ---
            } elseif ($adhesionForm->get('changeGroupe')->getData()) {
                $groupeChoisi = $adhesionForm->get('changeGroupe')->getData();
                $adhesion->setGroupe($groupeChoisi);
                $user->setGroupe($groupeChoisi);

            // --- Sinon garder le groupe actuel ---
            } else {
                $groupeActuel = $user->getGroupe();
                if (!$groupeActuel) {
                    throw new \LogicException('Le user doit avoir un groupe pour adhérer.');
                }
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
