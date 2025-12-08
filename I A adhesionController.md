I A adhesionController.md             
<?php

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
    {
        $user = new User();
        $form = $this->createForm(AdhesionFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($nouveauNom = $form->get('nouveau_groupe')->getData()) {
                $nouveauGroupe = (new Groupe())->setNom($nouveauNom);
                $entityManager->persist($nouveauGroupe);
                $user->setGroupe($nouveauGroupe);
            }

            if ($form->has('isOpen') && $user->isReferent() && $user->getGroupe()) {
                $user->getGroupe()->setIsOpen($form->get('isOpen')->getData());
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_adhesion');
        }

        return $this->render('adhesion/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
Création de l’objet et du formulaire
User neuf: new User() crée l’objet qui sera hydraté par le formulaire.

Formulaire lié: createForm(AdhesionFormType::class, $user) lie le FormType à l’objet. Grâce à data_class, les champs mappés mettent à jour $user.

Hydratation requête: handleRequest($request) remplit le formulaire avec les données POST quand on soumet.

Soumission et validation
Contrôle d’état: isSubmitted() vérifie qu’un POST a eu lieu, isValid() déclenche la validation (contraintes, types, etc.).

Si OK: on exécute la logique métier, puis on persiste et on flush.

Création d’un nouveau groupe (champ non mappé)
Récupération: get('nouveau_groupe')->getData() lit la valeur du champ libre (non mappé).

Condition: si l’utilisateur a saisi un nouveau nom, on crée un Groupe, on le persist, puis on lie User à ce nouveau groupe via setGroupe.

Pourquoi non mappé: ce champ n’existe pas sur User; on l’utilise pour déclencher une action (créer un Groupe) plutôt que pour hydrater User.

Mise à jour de isOpen pour le groupe référent
Présence du champ: form->has('isOpen') car il est ajouté dynamiquement seulement si le user est référent.

Sécurité logique: on vérifie que le user est référent et qu’il a bien un groupe.

Mise à jour: setIsOpen(...) applique la valeur au groupe lié. Ce champ est non mappé au User, donc on doit le copier à la main sur l’entité Groupe.

Persistence et redirection
Persistance: persist($user) programme l’insertion/mise à jour; flush() exécute les requêtes SQL.

Redirection: redirectToRoute('app_adhesion') évite la double soumission en rechargeant la page (pattern Post/Redirect/Get).

Rendu du formulaire
Affichage: si le formulaire n’est pas soumis ou invalide, on rend la vue avec form->createView().

Twig: dans adhesion/index.html.twig, tu affiches le formulaire avec {{ form(form) }} et gères les messages/erreurs.

Points clés à retenir
Mappé vs non mappé: les champs mappés hydratent automatiquement User; les non mappés nécessitent de récupérer leur valeur et d’agir en contrôleur.

Logique conditionnelle: isOpen n’existe que pour les référents; c’est pourquoi on teste has('isOpen') et l’état du user.

Lisibilité: chaque étape est courte et ciblée, ce qui rend le contrôleur simple et maintenable.