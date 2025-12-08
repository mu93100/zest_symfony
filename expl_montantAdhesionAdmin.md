**MONTANT ADHESION ADMIN**
 1. Insertion manuelle en base
dans ta table montant_adhesion via phpMyAdmin, Adminer, ou une requête SQL :
INSERT INTO montant_adhesion (libelle, montant) VALUES
('Adhésion solidaire', 5),
('Adhésion annuelle', 10),
('Adhésion de soutien', 15);

2. EasyAdmin
Puisque tu as déjà une interface EasyAdmin :

    Tu ajoutes le CRUD pour MontantAdhesion.
    Tu crées manuellement les 3 enregistrements via l’interface admin.
    Ensuite, tu peux les modifier ou en ajouter d’autres directement depuis EasyAdmin.
Exemple dans ton DashboardController 
yield MenuItem::linkToCrud('Montants d\'adhésion', 'fa fa-euro-sign', MontantAdhesion::class);

3. Migration Doctrine avec INSERT
Tu peux aussi créer une migration Doctrine qui insère les 3 montants par défaut. Exemple dans une migration générée :
public function up(Schema $schema): void
{
    $this->addSql("INSERT INTO montant_adhesion (libelle, montant) VALUES ('Adhésion solidaire', 5)");
    $this->addSql("INSERT INTO montant_adhesion (libelle, montant) VALUES ('Adhésion annuelle', 10)");
    $this->addSql("INSERT INTO montant_adhesion (libelle, montant) VALUES ('Adhésion de soutien', 15)");
}


**CRUD EASY ADMIN POUR MontantAdhesion**
🔹 1. Créer le CRUD Controller
// src/Controller/Admin/MontantAdhesionCrudController.php
namespace App\Controller\Admin;

use App\Entity\MontantAdhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MontantAdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MontantAdhesion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('libelle', 'Libellé'),
            IntegerField::new('montant', 'Montant (€)'),
        ];
    }
}

 2. Ajouter au Dashboard : 👉 Cela ajoute un menu dans EasyAdmin pour gérer les montants.
Dans ton DashboardController :
use App\Entity\MontantAdhesion;

public function configureMenuItems(): iterable
{
    yield MenuItem::linkToCrud('Montants d\'adhésion', 'fa fa-euro-sign', MontantAdhesion::class);
}

 3. Utilisation côté formulaire d’adhésion
Dans ton AdhesionFormType :👉 L’utilisateur voit les montants définis par l’admin, sous forme de cases radio.
->add('montantAdhesion', EntityType::class, [
    'class' => MontantAdhesion::class,
    'choice_label' => fn(MontantAdhesion $m) => $m->getLibelle() . ' (' . $m->getMontant() . ' €)',
    'expanded' => true, // cases radio
    'multiple' => false,
])
tu as un CRUD EasyAdmin pour gérer les montants (libelle + montant).

Tu ajoutes le menu dans ton Dashboard.

Tu relies Adhesion à MontantAdhesion via ManyToOne.

L’utilisateur choisit parmi les montants disponibles, et l’admin peut les modifier à tout moment.



------------------
**CRUD EasyAdmin pour l’entité Adhesion** afin que ton admin puisse gérer les adhésions (voir le User, le Groupe, le Montant choisi, et valider le paiement).
🔹 1. CRUD Controller

`// src/Controller/Admin/AdhesionCrudController.php
namespace App\Controller\Admin;

use App\Entity\Adhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class AdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adhesion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            // 🔹 Relations
            AssociationField::new('user', 'Utilisateur'),
            AssociationField::new('groupe', 'Groupe'),
            AssociationField::new('montantAdhesion', 'Montant choisi'),

            // 🔹 Saison et date
            TextField::new('saison', 'Saison'),
            DateTimeField::new('dateAdhesion', 'Date d\'adhésion'),

            // 🔹 Champs libres
            TextField::new('attentes', 'Attentes')->hideOnIndex(),
            TextField::new('competences', 'Compétences')->hideOnIndex(),

            // 🔹 Paiement validé par admin
            BooleanField::new('paiement', 'Paiement validé'),
        ];
    }
}

2- Ajouter au Dashboard

Dans ton DashboardController : 👉 Cela ajoute un menu “Adhésions” dans EasyAdmin.
use App\Entity\Adhesion;

public function configureMenuItems(): iterable
{
    yield MenuItem::linkToCrud('Adhésions', 'fa fa-users', Adhesion::class);
}

🔹 3. Ce que l’admin peut faire

    Voir la liste des adhésions par saison.
    Filtrer par utilisateur ou groupe.
    Modifier le montant choisi (si besoin).
    Valider le paiement (paiement = true).
    Consulter les attentes et compétences renseignées par l’utilisateur.
✅ Résumé
    -Tu as maintenant un CRUD EasyAdmin pour Adhesion.
    -L’admin peut gérer les adhésions annuelles, valider les paiements, et voir les infos des utilisateurs.
    -Couplé avec le CRUD MontantAdhesion, tu as une gestion complète des tarifs et des adhésions.

    
    
**CRUD EasyAdmin complet pour l’entité Adhesion**, avec un filtre par saison pour que l’admin puisse afficher rapidement les adhésions de 2025/2026, 2026/2027, etc

1- make:CRUD Controller
// src/Controller/Admin/AdhesionCrudController.php
namespace App\Controller\Admin;

use App\Entity\Adhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class AdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adhesion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            // 🔹 Relations
            AssociationField::new('user', 'Utilisateur'),
            AssociationField::new('groupe', 'Groupe'),
            AssociationField::new('montantAdhesion', 'Montant choisi'),

            // 🔹 Saison et date
            TextField::new('saison', 'Saison'),
            DateTimeField::new('dateAdhesion', 'Date d\'adhésion'),

            // 🔹 Champs libres
            TextField::new('attentes', 'Attentes')->hideOnIndex(),
            TextField::new('competences', 'Compétences')->hideOnIndex(),

            // 🔹 Paiement validé par admin
            BooleanField::new('paiement', 'Paiement validé'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('saison', 'Filtrer par saison'));
    }
}

2. Ajouter au Dashboard

Dans ton DashboardController :
use App\Entity\Adhesion;

public function configureMenuItems(): iterable
{
    yield MenuItem::linkToCrud('Adhésions', 'fa fa-users', Adhesion::class);
}

3. Résultat côté admin
    L’admin voit la liste des adhésions.
    Peut filtrer par saison (2025/2026, 2026/2027…).
    Peut valider le paiement (paiement = true).
    Peut consulter les attentes et compétences renseignées par l’utilisateur.
    Peut modifier le montant choisi si nécessaire.

✅ Résumé
    Tu as un CRUD EasyAdmin pour Adhesion.
    Tu as un filtre par saison pour naviguer facilement entre les années.
    Tu gardes une gestion claire : l’admin valide les paiements et peut gérer les montants via le CRUD MontantAdhesion.

**EasyAdmin CRUD pour Adhesion avec filtres combinés** (saison + groupe). Cela permettra à ton admin de naviguer facilement dans les adhésions par année et par collectif.
🔹 CRUD Controller avec filtres
// src/Controller/Admin/AdhesionCrudController.php
namespace App\Controller\Admin;

use App\Entity\Adhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class AdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adhesion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            // 🔹 Relations
            AssociationField::new('user', 'Utilisateur'),
            AssociationField::new('groupe', 'Groupe'),
            AssociationField::new('montantAdhesion', 'Montant choisi'),

            // 🔹 Saison et date
            TextField::new('saison', 'Saison'),
            DateTimeField::new('dateAdhesion', 'Date d\'adhésion'),

            // 🔹 Champs libres
            TextField::new('attentes', 'Attentes')->hideOnIndex(),
            TextField::new('competences', 'Compétences')->hideOnIndex(),

            // 🔹 Paiement validé par admin
            BooleanField::new('paiement', 'Paiement validé')
            ->renderAsSwitch(false) // affichage en badge plutôt qu’en switch
            ->setCssClass('badge badge-warning'); // couleur pour attirer l’attention

        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('saison', 'Filtrer par saison'))
            ->add(EntityFilter::new('groupe', 'Filtrer par groupe'));
            ->add(BooleanFilter::new('paiement', 'Paiement validé'));

    }
}
Résultat côté admin
    L’admin peut afficher toutes les adhésions d’une saison donnée (2025/2026, 2026/2027…).
    L’admin peut filtrer par groupe (ex. “Aubervilliers”).
    Les filtres peuvent être combinés : par exemple, toutes les adhésions du groupe “Aubervilliers” pour la saison 2025/2026.
    L’admin peut valider le paiement, modifier le montant choisi, consulter les attentes et compétences.

**automatiser l'envoi de mails pour relance adhésion non payée :**
logique métier
Cibler les adhésions non validées :
$adhesionsNonValidees = $adhesionRepository->findBy(['paiement' => false]);
3 POSSIBILITÉ POUR METTRE CETTE LIGNE DE CODE
1. Dans un Controller Symfony 👉 Ici, tu passes la liste à un template Twig.
// src/Controller/Admin/AdhesionController.php
#[Route('/admin/adhesions-en-attente', name: 'adhesions_non_validees')]
public function adhesionsNonValidees(AdhesionRepository $adhesionRepository): Response
{
    $adhesionsNonValidees = $adhesionRepository->findBy(['paiement' => false]);

    return $this->render('admin/adhesions_non_validees.html.twig', [
        'adhesions' => $adhesionsNonValidees,
    ]);
}
2. Dans ton Dashboard EasyAdmin
Si tu veux afficher un compteur ou une liste rapide dès l’accueil admin :Tu peux ensuite afficher {{ adhesionsNonValidees|length }} dans ton dashboard.html.twig.

// src/Controller/Admin/DashboardController.php
#[Route('/admin', name: 'admin')]
public function index(AdhesionRepository $adhesionRepository): Response
{
    $adhesionsNonValidees = $adhesionRepository->findBy(['paiement' => false]);

    return $this->render('admin/dashboard.html.twig', [
        'adhesionsNonValidees' => $adhesionsNonValidees,
    ]);
}

Dans une Commande Symfony

 3. Si tu veux automatiser (par exemple pour envoyer des mails de relance) :👉 Tu lances ensuite avec php bin/console app:relance-paiement.
// src/Command/RelancePaiementCommand.php
protected function execute(InputInterface $input, OutputInterface $output): int
{
    $adhesionsNonValidees = $this->adhesionRepository->findBy(['paiement' => false]);

    $output->writeln(count($adhesionsNonValidees) . ' adhésions en attente de paiement.');
    return Command::SUCCESS;
}
✅ Résumé

    Tu mets cette ligne dans un Controller, un Dashboard, ou une Commande, selon ce que tu veux faire : afficher, compter, ou relancer.

    Dans tous les cas, il faut injecter AdhesionRepository dans ton fichier (via l’argument de méthode ou le constructeur).

dans ton Dashboard EasyAdmin pour afficher directement le nombre d’adhésions en attente de validation (paiement non validé).
🔹**DashboardController avec compteur**
// src/Controller/Admin/DashboardController.php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Adhesion;
use App\Entity\MontantAdhesion;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // 🔹 Compteur des adhésions non validées
        $adhesionsNonValidees = $this->em->getRepository(Adhesion::class)->count(['paiement' => false]);

        // 🔹 Compteur des adhésions validées
        $adhesionsValidees = $this->em->getRepository(Adhesion::class)->count(['paiement' => true]);

        return $this->render('admin/dashboard.html.twig', [
            'adhesionsNonValidees' => $adhesionsNonValidees,
            'adhesionsValidees' => $adhesionsValidees,
        ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Adhésions', 'fa fa-id-card', Adhesion::class);
        yield MenuItem::linkToCrud('Montants d\'adhésion', 'fa fa-euro-sign', MontantAdhesion::class);
    }
}
**dans template dashboard.html.twig**
{% extends '@EasyAdmin/page/content.html.twig' %}

{% block content %}
    <h1>Tableau de bord</h1>

    <div style="display:flex; gap:2rem;">
        <div style="background:#f8d7da; padding:1rem; border-radius:8px;">
            <h2>⚠️ Adhésions en attente</h2>
            <p><strong>{{ adhesionsNonValidees }}</strong> adhésions non validées</p>
        </div>

        <div style="background:#d4edda; padding:1rem; border-radius:8px;">
            <h2>✅ Adhésions validées</h2>
            <p><strong>{{ adhesionsValidees }}</strong> adhésions validées</p>
        </div>
    </div>
{% endblock %}
 Résultat côté admin
    Dès l’accueil, l’admin voit :
        ⚠️ le nombre d’adhésions en attente de validation (paiement non validé).
        ✅ le nombre d’adhésions validées.
    Les blocs sont colorés (rouge pour en attente, vert pour validées).
    Tu peux cliquer ensuite sur le menu “Adhésions” pour gérer les détails.

**AUTOMATISATION ENVOI DE MAIOLS RELANCE -- SUITE**
    -Vérifier la date d’adhésion pour ne relancer qu’après un certain délai (ex. 7 jours sans validation).
    -Générer un contenu d’email personnalisé (nom du membre, saison, montant choisi).

Utiliser le composant Mailer de Symfony
Symfony fournit un composant Mailer très simple :
**DANS // src/Controller/Admin/RelanceController.php**

namespace App\Controller\Admin;

use App\Entity\Adhesion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class RelanceController extends AbstractController
{
    #[Route('/admin/relance/{id}', name: 'relance_paiement')]
    public function relancePaiement(MailerInterface $mailer, Adhesion $adhesion): void
    {
        $email = (new Email())
            ->from('admin@tonsite.fr')
            ->to($adhesion->getUser()->getEmail())
            ->subject('Relance : Paiement de votre adhésion')
            ->html("
                Bonjour {$adhesion->getUser()->getNom()},<br><br>
                Nous avons bien reçu votre demande d’adhésion pour la saison {$adhesion->getSaison  ()}.
                Cependant, le paiement n’a pas encore été validé.<br><br>
                Merci de régulariser votre situation pour finaliser votre adhésion.<br><br>
                L’équipe Zest_site
            ");

        $mailer->send($email);

        return $this->redirectToRoute('admin'); // retour au dashboard
    }
}
Résumé

    Controller → déclenchement manuel (ex. bouton dans EasyAdmin).

    Service → logique réutilisable et propre.

    Commande → automatisation avec cron.

👉 La meilleure pratique est de mettre relancePaiement() dans un Service Symfony (src/Service/RelancePaiementService.php) et de l’appeler depuis ton Dashboard ou une commande. Ça garde ton code clair et réutilisable.

Service : il n’y a pas de make:service. 👉 Tu crées simplement le fichier toi-même dans src/Service/RelancePaiementService.php. Symfony reconnaît automatiquement la classe grâce à l’autoloading.
src/
├── Controller/
├── Entity/
├── Repository/
├── Service/   ← tu le crées ici
└── ...
Créer ton fichier de service Par exemple RelancePaiementService.php :
// src/Service/RelancePaiementService.php
namespace App\Service;

use App\Entity\Adhesion;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RelancePaiementService
{
    public function __construct(private MailerInterface $mailer) {}

    public function relancePaiement(Adhesion $adhesion): void
    {
        $email = (new Email())
            ->from('admin@tonsite.fr')
            ->to($adhesion->getUser()->getEmail())
            ->subject('Relance : Paiement de votre adhésion')
            ->html("
                Bonjour {$adhesion->getUser()->getNom()},<br><br>
                Votre adhésion pour la saison {$adhesion->getSaison()} est en attente de paiement.<br>
                Merci de régulariser votre situation pour finaliser votre inscription.<br><br>
                L’équipe Zest_site
            ");

        $this->mailer->send($email);
    }
}

Appeler ton service Depuis un Controller : Depuis une Commande Symfony (pour automatiser avec cron).  classes métiers RelancePaiementService.
public function index(RelancePaiementService $relanceService, AdhesionRepository $repo)
{
    $adhesionsNonValidees = $repo->findBy(['paiement' => false]);

    foreach ($adhesionsNonValidees as $adhesion) {
        $relanceService->relancePaiement($adhesion);
    }

    // ...
}
à mettre dans DashboardController.php :::
// src/Controller/Admin/DashboardController.php
namespace App\Controller\Admin;

use App\Entity\Adhesion;
use App\Repository\AdhesionRepository;
use App\Service\RelancePaiementService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(RelancePaiementService $relanceService, AdhesionRepository $repo): Response
    {
        // 🔹 Récupérer les adhésions non validées
        $adhesionsNonValidees = $repo->findBy(['paiement' => false]);

        // 🔹 Envoyer une relance pour chacune
        foreach ($adhesionsNonValidees as $adhesion) {
            $relanceService->relancePaiement($adhesion);
        }

        // 🔹 Afficher le dashboard
        return $this->render('admin/dashboard.html.twig', [
            'adhesionsNonValidees' => $adhesionsNonValidees,
        ]);
    }
}



php bin/console make:controller RelanceController === → crée un fichier src/Controller/RelanceController.php + un template Twig.
php bin/console make:command app:relance-paiement===→ crée un fichier src/Command/RelancePaiementCommand.php.
