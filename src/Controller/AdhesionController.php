<?php
namespace App\Controller;

use App\Entity\Adhesion;
use App\Entity\Groupe;
use App\Entity\User;
use App\Service\SaisonContext;
use App\Entity\GroupeReferentSaison;
use App\Form\AdhesionFormType;
use App\Repository\SaisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class AdhesionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SaisonContext $saisonContext
    ) {}

    #[Route('/adhesion', name: 'app_adhesion')]
    public function index(Request $request, SaisonRepository $saisonRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('[ Tu dois être connecté pour adhérer ]');
        } 
        
        // ------------- Créer une nouvelle adhésion
        $adhesion = new Adhesion();
        $adhesion->setPaiementValide(false);
        // ------------- Saison en cours
        $saisonEnCours = $this->saisonContext->getSaison();

        if ($saisonEnCours) {
            $adhesion->setSaison($saisonEnCours);
        } else{
            $this->addFlash('danger',"[ Impossible d'adhérer pour la saison actuelle. Merci de contacter <strong>adhesion@corto-zest.org</strong> ]"); 
            return $this->redirectToRoute('app_accueil');
        }

        // ------------- Créer le formulaire
        $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion, [
            'user' => $user,
        ]);
        $adhesionForm->handleRequest($request);

        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
            
            // -------------------------------------- GESTION DU GROUPE 
            $groupeFinal = null;

            // création nouveau groupe
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            if ($nouveauNom) {
                $groupeExistant = $this->em->getRepository(Groupe::class) // vérifier si groupe existe
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
                    $this->em->persist($groupeFinal);
                }
            }
            // changement de groupe pour groupe existant
            elseif ($changeGroupe = $adhesionForm->get('changeGroupe')->getData()) {
                $groupeFinal = $changeGroupe;
            } else {
                $groupeFinal = $user->getGroupe(); // sinon : garder son groupe actuel
                
                if (!$groupeFinal) {
                    throw new \LogicException('[ Le user doit avoir un groupe pour adhérer ]');
                }
            }

            // Modifications éventuelles du groupe final
            $nouvelleAdresse = $adhesionForm->get('adresseDistrib')->getData();
            $nouveauCodePostal = $adhesionForm->get('codePostal')->getData();
            $nouvelleVille = $adhesionForm->get('ville')->getData();
            
            if ($nouvelleAdresse !== null && $nouvelleAdresse !== '') {
                $groupeFinal->setAdresseDistrib($nouvelleAdresse);
            }
            if ($nouveauCodePostal !== null && $nouveauCodePostal !== '') {
                $groupeFinal->setCodePostal($nouveauCodePostal);
            }
            if ($nouvelleVille !== null && $nouvelleVille !== '') {
                $groupeFinal->setVille($nouvelleVille);
            }
            $groupeFinal->setIsOpen((bool) $adhesionForm->get('isOpen')->getData());

            // -------------------------------------- lier ADHESION et USER au groupe final
            $adhesion->setGroupe($groupeFinal);
            $user->setGroupe($groupeFinal);

            // -------------------------------------- GESTION REFERENT
$isReferent = $adhesionForm->get('isReferent')->getData(); // récupérer la case "Je suis référent"
$saison = $this->saisonContext->getSaison(); //récuperer saison active

if ($isReferent) { // si l'utilisateur est référent
    // chercher un pivot existant
    $pivot = $this->em->getRepository(GroupeReferentSaison::class) // récupérer ou créer le pivot
        ->findOneBy(['groupe' => $groupeFinal, 'saison' => $saison]);

    // créer pivot seulement si nécessaire (pivot = lien avec entity GroupeReferentSaison)
    if (!$pivot) {
        $pivot = new GroupeReferentSaison();
        $pivot->setGroupe($groupeFinal);
        $pivot->setSaison($saison);
        $this->em->persist($pivot);
    }

    // on définit le référent
    $pivot->setReferent($user);

} else {
    // si l'utilisateur n'est pas référent mais était référent → on supprime son rôle éventuel
    $pivot = $this->em->getRepository(GroupeReferentSaison::class)
        ->findOneBy(['groupe' => $groupeFinal, 'saison' => $saison, 'referent' => $user]);

    if ($pivot) {
        $pivot->setReferent(null);
    }
}

            // -------------------------------------- GESTION ADHESION
            // --------- lier adhesion à user
            $adhesion->setUser($user);

            // ---------- sauvegarder
            $this->em->persist($adhesion);
            $this->em->persist($user);
            $this->em->persist($groupeFinal); // Au cas où
            $this->em->flush();

            // -------------------------------------- FLASH + REDIRECTION
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
