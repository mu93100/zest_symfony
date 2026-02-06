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
        
        // ------------- Créer une nouvelle adhésion
        $adhesion = new Adhesion();

        // ------------- Saison en cours
        $saisonEnCours = $saisonRepository->findSaisonCourante();

        if ($saisonEnCours) {
            $adhesion->setSaison($saisonEnCours);
        } else{
            $this->addFlash("[ Impossible d'adhérer pour la saison actuelle. Merci de contacter <strong>adhesion@corto-zest.org</strong> ]"); 
            return $this->redirectToRoute('app_accueil');
        }

        // ------------- Créer le formulaire
        $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion, [
            'user' => $user,
        ]);
        $adhesionForm->handleRequest($request);

        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
            
            // =====================================================================
            // 1. GESTION DU GROUPE (PRIORITÉ ABSOLUE)
            // =====================================================================
            $groupeFinal = null;

            // PRIORITÉ 1 : Nouveau groupe
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            if ($nouveauNom) {
                // Vérifier doublon
                $groupeExistant = $entityManager->getRepository(Groupe::class)
                    ->findOneBy(['nom' => $nouveauNom]);
                
                if ($groupeExistant) {
                    $this->addFlash('warning', 'Groupe "' . $nouveauNom . '" trouvé et utilisé.');
                    $groupeFinal = $groupeExistant;
                } else {
                    $groupeFinal = new Groupe();
                    $groupeFinal->setNom($nouveauNom);
                    $groupeFinal->setAdresseDistrib($adhesionForm->get('adresseDistribNouveau')->getData() ?? '');
                    $groupeFinal->setVille($adhesionForm->get('villeNouveau')->getData() ?? 'Sans ville');
                    $groupeFinal->setIsOpen((bool) $adhesionForm->get('isOpen')->getData());
                    $entityManager->persist($groupeFinal);
                }
            }
            // PRIORITÉ 2 : Changement vers groupe existant
            elseif ($changeGroupe = $adhesionForm->get('changeGroupe')->getData()) {
                $groupeFinal = $changeGroupe;
            }
            // PRIORITÉ 3 : Garder groupe actuel
            else {
                $groupeFinal = $user->getGroupe();
                if (!$groupeFinal) {
                    throw new \LogicException('[ Le user doit avoir un groupe pour adhérer ]');
                }
            }

            // Modifications éventuelles du groupe final
            $nouvelleAdresse = $adhesionForm->get('adresseDistrib')->getData();
            $nouvelleVille = $adhesionForm->get('ville')->getData();
            if ($nouvelleAdresse !== null && $nouvelleAdresse !== '') {
                $groupeFinal->setAdresseDistrib($nouvelleAdresse);
            }
            if ($nouvelleVille !== null && $nouvelleVille !== '') {
                $groupeFinal->setVille($nouvelleVille);
            }
            $groupeFinal->setIsOpen((bool) $adhesionForm->get('isOpen')->getData());

            // =====================================================================
            // 2. LIER L'ADHESION ET USER AU GROUPE FINAL
            // =====================================================================
            $adhesion->setGroupe($groupeFinal);
            $user->setGroupe($groupeFinal);

            // =====================================================================
            // 3. GESTION REFERENT
            // =====================================================================
            $isReferent = $adhesionForm->get('isReferent')->getData();
            if ($isReferent) {
                if ($groupeFinal->getReferent() !== $user) {
                    $groupeFinal->setReferent($user);
                    $user->setGroupeReferent($groupeFinal);
                }
            } else {
                if ($groupeFinal->getReferent() === $user) {
                    $groupeFinal->setReferent(null);
                    $user->setGroupeReferent(null);
                }
            }

            // =====================================================================
            // 4. LIER USER A L'ADHESION
            // =====================================================================
            $adhesion->setUser($user);

            // =====================================================================
            // 5. SAUVEGARDE
            // =====================================================================
            $entityManager->persist($adhesion);
            $entityManager->persist($user);
            $entityManager->persist($groupeFinal); // Au cas où
            $entityManager->flush();

            // =====================================================================
            // 6. FLASH + REDIRECTION
            // =====================================================================
            $this->addFlash('success', $this->renderView('adhesion/_recap.html.twig', [
                'user' => $user,
                'groupe' => $adhesion->getGroupe(),
                'saison' => $adhesion->getSaison(),
                'motivations' => $adhesion->getMotivations(),
                'montantAdhesion' => $adhesion->getMontantAdhesion(),
            ]));

            return $this->redirectToRoute('app_accueil');
        }

        // ------------- retour si formulaire non soumis ou invalide
        return $this->render('adhesion/index.html.twig', [
            'adhesionForm' => $adhesionForm->createView(),
            'saison' => $saisonEnCours,
            'user' => $user,
            'montantAdhesion' => $adhesion->getMontantAdhesion(),
        ]);
    }
}
