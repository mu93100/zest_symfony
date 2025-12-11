<?php

namespace App\Controller;

use App\Entity\Adhesion;
use App\Entity\Groupe;
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

        // ⚡ Ici tu passes le user connecté au FormType
        $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion, [
            'user' => $this->getUser(),
        ]);
            
        // ⚡ Construire le formulaire basé sur AdhesionFormType
        $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion);
        $adhesionForm->handleRequest($request);

        // ⚡ Sauvegarde si soumis et valide
        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
            $adhesion = $adhesionForm->getData();
            
            // Si "Je change de groupe" coché
            if ($adhesionForm->get('changeGroupe')->getData()) {
                $adhesion->setGroupe($adhesionForm->get('nouveauGroupe')->getData());
            }
            // 1️⃣ Gestion du champ "nouveauGroupe"
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            if ($nouveauNom) {
                $nouveauGroupe = new Groupe();
                $nouveauGroupe->setNom($nouveauNom);
                $entityManager->persist($nouveauGroupe);
                $adhesion->setGroupe($nouveauGroupe);
            }

            // 2️⃣ Gestion du champ "isOpen" si l’utilisateur est référent
            $isOpen = $adhesionForm->has('isOpen') ? $adhesionForm->get('isOpen')->getData() : null;
            if ($isOpen !== null && $adhesion->isReferent() && $adhesion->getGroupe()) {
                $adhesion->getGroupe()->setIsOpen($isOpen);
            }

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
        ]);
    }
}
