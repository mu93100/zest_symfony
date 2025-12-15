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




// ajout perplexity : 
// $adhesion = new Adhesion();
// $form = $this->createForm(AdhesionFormType::class, $adhesion, [
//     'user' => $this->getUser()
// ]);

final class AdhesionController extends AbstractController
{
    #[Route('/adhesion', name: 'app_adhesion')]
    public function index(Request $request, EntityManagerInterface $entityManager, SaisonRepository $saisonRepository): Response
    // Injection: Request contient la requête HTTP (GET/POST), 
    // EntityManagerInterface sert à persister/flush les entités.
    { // ⚡ Création d'une nouvelle adhesion/ objet qui sera hydraté par le formulaire.
        $adhesion = new Adhesion();

        // 2. Récupérer la saison en cours (dernière créée)
        $saisonEnCours = $saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);
        if ($saisonEnCours) {
            $adhesion->setSaison($saisonEnCours);
        }

        // ⚡ Ici tu passes le user connecté au FormType + Construire le formulaire basé sur AdhesionFormType
        $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion, [
            'user' => $this->getUser(),
        ]);
        $adhesionForm->handleRequest($request);

        // ⚡ Sauvegarde si soumis et valide
        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
            $adhesion = $adhesionForm->getData();
            $user = $this->getUser();

            // 1️⃣ Création d’un nouveau groupe si un nom est saisi
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            if ($nouveauNom) {
                $nouveauGroupe = new Groupe();
                $nouveauGroupe->setNom($nouveauNom);
                // optionnel : remplir adresse / ville à partir des champs non mappés
                $nouveauGroupe->setadresseDistrib($adhesionForm->get('adresseDistribution')->getData());
                $nouveauGroupe->setVille($adhesionForm->get('ville')->getData());

                $entityManager->persist($nouveauGroupe);
                $adhesion->setGroupe($nouveauGroupe);

                if ($user) {
                    $user->setGroupe($nouveauGroupe);
                    $user->setIsReferent(true);
                }

                // 2️⃣ Sinon, si "Je change de groupe" est coché, on prend le groupe choisi
            } elseif ($adhesionForm->get('changeGroupe')->getData()) {
                $groupeChoisi = $adhesionForm->get('groupe')->getData();
                if ($groupeChoisi) {
                    $adhesion->setGroupe($groupeChoisi);
                    if ($user) {
                        $user->setGroupe($groupeChoisi);
                    }
                }

                // 3️⃣ Sinon, on reste sur le groupe actuel du user
            } else {
                if ($user && $user->getGroupe()) {
                    $adhesion->setGroupe($user->getGroupe());
                }
            }

            // Lier l’adhésion au user connecté
            if ($user) {
                $adhesion->setUser($user);
            }
            // SUREMENT A ENLEVER
            // // 2️⃣ Gestion du champ "isOpen" si l’utilisateur est référent
            // $isOpen = $adhesionForm->has('isOpen') ? $adhesionForm->get('isOpen')->getData() : null;
            // if ($isOpen !== null && $adhesion->isReferent() && $adhesion->getGroupe()) {
            //     $adhesion->getGroupe()->setIsOpen($isOpen);
            // }

            // ⚡ Sauvegarde en base
            $entityManager->persist($adhesion);
            $entityManager->flush();

            // ⚡ Redirection ou message de confirmation
            $this->addFlash('success', 'A D H E S I O N    E N R E G I S T R E E   [ merci de la régler par virement sous 8 jours à Corto-Zest IBAN FR 0000 0000 0000 0000 000 ]');
            return $this->redirectToRoute('app_adhesion');
        }

        // ⚡ Affichage du formulaire
        return $this->render('adhesion/index.html.twig', [
            'adhesionForm' => $adhesionForm->createView(),
            'saison' => $saisonEnCours,
            'user' => $this->getUser(),
        ]);
    }
}
