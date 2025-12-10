\# Commandes utiles Symfony – Récapitulatif
NOV 2025
**CTRL +C --> pour quitter une commande symfo en plein milieu (Qd erreur)**

ouvrir truc facu 
ouvrir Wamp PUIS démarrer serveur local

TOUT S'ECRIT DS TERMINAL

pour commencer en clonant un doss de facundo
1)-    symfony serve -d
2)-    symfony serve
3)-    composer install
4)-    php bin/console doctrine:database:create new
5)-    php bin/console make:migration
6)-    php bin/console doctrine:migrations:migrate
=================================================
GÉNÉRATION DE BASE POUR Nx DOSS symfonysymfony serve
=================================================
SANS CLONE DE DOSS FACUNDO 	**ZEST**

Créer un projet Symfony :

>créer un doss sur ordi >ouvrir terminal
**symfony new nom\_projet --webapp**

Démarrer le serveur local : (ds VSCODE term bash)
**symfony serve**

**symfony serve -d**         # en arrière-plan --> tourne tjs en arr plan et pas de symfony serve à refaire à chaque fois que l'on ouvre le doss
symfony open:local       # ouvre le navigateur (((ou **on click sur url ds rectangle vert http://127.0.0.1:8001**)

Vérifier config :
**symfony check:requirements**
**> .env --> vérif si on est bien en SQL (avec PhpMyAdmin)** 
**sinon --> DATABASE\_URL="mysql://root: MOT DE PASSE@127.0.0.1:3306/NOM BDD?serverVersion=8.0.32\&charset=utf8mb4"**
**+ changer MDP et nom BDD**

CREATION BDD + entité (= table/ symfo nous demande de édfinir les champs en auto)  
-->**Nx terminal bash**

**php bin/console doctrine:database:create**
**1  php bin/console make:user**
**php bin/console make:entity   ça crée des fichiers src/Entity/Entity.php** 
**php bin/console make:migration**
**
  pour appliquer les changements à BDD.**
**php bin/console doctrine:schema:validate   pour verif mapping doctrine correspond bien à la BDD**

**
QUAND on revient à Add another property? = on revient à l'entity à laquelle on voulait ajouter une FK
(si on met ttes les FK à la fin : on refait php bin/console make:entity ((POUR CREATE ou UPDATE)))

FK / relation unidirectionnelle (1FK enfant ds table parent) OU bidirectionnelle (1FK dans chaque table = )
Pour**Do you want to activate orphanRemoval on your relationship?** => NO si on veut pouvoir modifier et updater. sinon SYMFONY SUPPRIME DES ENTREES
A T T E N T I O N   :  
Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > recette

 Field type (enter ? to see all types) [string]:
 > relation
relation

 What class should this entity be related to?:
 > **Recette**  **ET NON PAS recette / avec MAJUSCULE**
- OneToMany / ManyToOne : une seule FK côté "Many".
- ManyToMany : là oui, une table de jointure est créée avec deux FK (une vers chaque table).
- orphanRemoval : ne change pas le nombre de FK, mais la façon dont Doctrine gère la suppression des entités liées.

changerA User is "orphaned" when it is removed from its related montantAdhesion. e.g. $montantAdhesion->removeUser($user) NOTE: If a User may *change* from one montantAdhesion to another, answer "no". Do you want to automatically delete orphaned App\Entity\User objects (orphanRemoval)? (yes/no) [no]:

si **php bin/console doctrine:schema:validate ne marche pas** = verif ds phpMyAdmin > ds opérations (en Ht à Dte) si moteur de stockage est en InnoDB et non pas MyISAM > remettre tout en InnoDB et
**> php bin/console doctrine:schema:update --force**
**> php bin/console doctrine:schema:validate**

les entités sont créées /src/entity/User.php ... ETC
+ ça a créé des repo /src/repository/UserRepository.php ... ETC

pour updater quand on a modifié des tables (en prenant en compte la dernière migration)
php bin/console doctrine:migrations:version --add <numéro_de_version>
OU forcer Doctrine à aligner directement la base sur tes entités :
php bin/console doctrine:schema:update --force
OU pour voir les requêtes sans les exécuter :
php bin/console doctrine:schema:update --dump-sql
⚠️ Attention : cette méthode peut supprimer ou modifier des colonnes sans garde-fou → risque de perte de données. Elle est utile en phase de développement, mais pas en production.

on crée les controlleurs
**php bin/console make:controller NomController**
on crée l'authentification login
**php bin/console make:security:form-login**

symfony demande le bundle reset password
**composer require symfonycasts/reset-password-bundle**
on crée le reset MDP / reinitialisation MDP
**php bin/console make:reset-password**
-> ça crée 4 fichiers twig ds templates/reset_password
-> ca crée dans config/package reset_password.yaml
**php bin/console make:migration**
**php bin/console doctrine:migrations:migrate**

créer un form d'enregistrement
**php bin/console make:registration-form**           Create a new registration form system

créer un fichier dans un dossier existant
**php bin/console make:command MailTestCommand**

créer un dossier
**mkdir src/Command**


E C R I R E       E C R I R E           E C R I R E

=======================
CONSOLE SYMFONY
=======================
**Afficher toutes les commandes dispo ds symfo:**
php bin/console

**Lister les routes /Tu verras les chemins, les noms de routes, les contrôleurs associés, etc.**
Idéal pour vérifier que tes routes sont bien configurées :
php bin/console debug:router

**Lister les services :**
php bin/console debug:container

(((Le conteneur de dépendances (aussi appelé service container) est un élément central dans Symfony. C’est lui qui gère tous les objets et services dont ton application a besoin pour fonctionner.
Le conteneur de dépendances est comme un super gestionnaire d’objets. Il crée, configure et fournit les bons objets (services) au bon moment, sans que tu aies à les instancier toi-même.

🔧 Exemple concret :
Imaginons que tu as besoin d’envoyer un email. Tu pourrais créer manuellement un objet Mailer, mais Symfony peut le faire pour toi :

php
public function \_\_construct(MailerInterface $mailer)
{
&nbsp;   $this->mailer = $mailer;
}

➡️ Ici, Symfony injecte automatiquement le service MailerInterface dans ton contrôleur grâce au conteneur.

📦 Que contient ce conteneur ?
\- Des services Symfony (comme le routeur, le cache, le mailer…)
\- Tes propres services (ceux que tu crées dans /src/Service)
\- Des services de bundles externes

Tu peux les explorer avec : bash --> php bin/console debug:container

🧩 Pourquoi c’est utile ?
\- Gain de temps : pas besoin de créer manuellement chaque objet.
\- Modularité : tu peux facilement remplacer un service par un autre.
\- Testabilité : tu peux injecter des versions simulées (mock) de tes services.

**Vider le cache :**
php bin/console cache:clear
Utilisation : Pour vider le cache de Symfony (routes, services, templates compilés…).
À faire après une modification importante de config ou en cas de bug étrange.

Voir la version de Symfony : ((Utile pour vérifier la compatibilité avec des bundles ou des fonctionnalités.))
php bin/console --version

=======================
GÉNÉRATION DE CODE
=======================
**Créer un contrôleur :**
php bin/console make:controller NomController

**Créer un utilisateur : MU  !!! CREER UNE TABLE USER AVEC ROLE ET password\_hashers +++ pour pouvoir créer le form de LOGIN)**
php bin/console make:user

**Créer une entité (produits)**:
php bin/console make:entity

**Créer une migration :**
php bin/console make:migration

**Appliquer une migration :**
php bin/console doctrine:migrations:migrate

**Créer un formulaire :**
php bin/console make:form NomType

**Créer un système d’auth :**
php bin/console make:auth OU **php bin/console make:security:form-login**

**Créer une interface CRUD :**
php bin/console make:crud Nom

=======================
DOCTRINE
=======================
**Créer la base de données :**
php bin/console doctrine:database:create

**Voir les entités :**
php bin/console doctrine:mapping:info

**Mise à jour schéma (à éviter en prod) :**
php bin/console doctrine:schema:update --force

**Exécuter une requête SQL :**
php bin/console doctrine:query:sql 'SELECT \* FROM user'

**Drop la base (⚠️ destructif) :**
php bin/console doctrine:database:drop --force

=======================
SÉCURITÉ
=======================
**Créer un authenticator :** 
php bin/console make:auth  OU **php bin/console make:security:form-login**
=> Cette commande génère :
Un SecurityController avec les routes de login/logout.
Les fichiers Twig pour le formulaire de connexion.
La configuration de sécurité dans security.yaml.
⚡ Donc : connexion = make:auth (et pas un contrôleur classique).

**créer reset password**
php bin/console make:reset-password
👉 Cette commande génère :
Un ResetPasswordController.
Les formulaires et services nécessaires pour envoyer un lien de réinitialisation par email.
Les vues Twig pour saisir un nouveau mot de passe.
⚡ Donc : réinitialisation MDP = make:reset-password (et pas un contrôleur classique).

**Créer un utilisateur :**
php bin/console make:user

**Créer un contrôleur de login :**
php bin/console make:controller SecurityController

=======================
DIVERS \& DEBUG
=======================
**Créer un service :**
Créer un fichier dans src/Service/ et Symfony le détecte automatiquement

**Lister les commandes disponibles :**
php bin/console list

**Voir la config d’un service :**
php bin/console debug:container App\\Service\\TonService

**Voir les routes :**
php bin/console debug:router

**Tester la BDD :**
php bin/console doctrine:query:sql 'SELECT NOW()'

-------------------------------------------------------------
=======================
BUNDLES
=======================
config/bundles.php : des bundles s'ajoutent automatiquement ++ on peut en rajouter d'autres
https://packagist.org
https://symfony.com/bundles

Les bundles dans Symfony sont comme des extensions ou des modules que tu peux ajouter à ton application pour lui donner de nouvelles fonctionnalités, sans tout coder toi-même. C’est un peu comme des plugins dans WordPress ou des apps sur ton téléphone 📱.

🧩 Définition simple :
Un bundle est un paquet de code réutilisable qui peut contenir :
Des contrôleurs
Des services
Des templates
Des configurations
Des assets (CSS, JS…)

🛠️ Pourquoi utiliser des bundles ?
Pour gagner du temps : tu n’as pas besoin de tout développer toi-même.
Pour ajouter des fonctionnalités rapidement (ex : sécurité, formulaire, API, etc.).
Pour organiser ton code de manière modulaire et propre.

📦 Exemples de bundles populaires :
Bundle			Fonction principale
-----------------------------------------------------------------------------
DoctrineBundle		Intégration de Doctrine ORM (base de données)
TwigBundle		Moteur de templates Twig
SecurityBundle		Gestion des utilisateurs, rôles, authentification
MakerBundle		Génération automatique de code (entités, contrôleurs…)
ApiPlatformBundle	Création d’API REST et GraphQL
DebugBundle		Outils de debug pendant le développement


--------------------------------------
BUNDLE **DASHBOARD ADMIN**
ajouter bundle easyadmin
composer require easycorp/easyadmin-bundle
php bin/console make:admin:dashboard

**aller ds DashboardController.php**

ajouter toutes les entités concernées par des modifications:
use App\Entity\User;
use App\Entity\Pole; ...ETC 

dans public function index(): Response
    {
**LAISSER**  return parent::index(); **pour travailler avec dashboard easyadmin**
et apres ::: yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
OU  I A, car page admin ne s'affiche pas :
return $this->redirectToRoute('admin_user_index');

**on rajoute**
yield MenuItem::linkToCrud('The Label', 'fas fa-user', User::class);
yield MenuItem::linkToCrud('The Label', 'fas fa-pole', Pole::class); ...ETC 

php bin/console make:admin:crud
**ca crée DashboardController.php et . UserCrudController.php**

on fait OK OK

⚠️ password : ne pas l’afficher ni l’éditer directement dans EasyAdmin (risque de stocker du texte brut).

⚠️ roles : remplacer JsonField par un ChoiceField avec cases à cocher pour plus d’ergonomie :
ds UserCrudController;php
public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('prenom'),
            ...
            ChoiceField::new('roles')
                ->setLabel('Rôles')
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                    'Vérifié' => 'ROLE_VERIFIED',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(), // cases à cocher
            ...
        ];}

**ON FAIT php bin/console make:admin:crud pour tous les entity que l'on veut pouvoir modifier créer ETC**

















-------------------------------------
**pour FORM**
data_class: User::class === le form est lié à entity USER -> Symfony va automatiquement hydrater les propriétés de User avec les champs du formulaire. avec  ->add('email') ETC

**si on des champs qui vont remplir d'autres entity (via des FK)** on a 'mapped' => false === c'est un champs non mappé
->add('nouveau_groupe', TextType::class, [
    'mapped' => false,
])
Tu devras récupérer sa valeur manuellement dans ton **contrôleur** avec
$nouveauNom = $form->get('nouveau_groupe')->getData();

Champ mappé → lié à une propriété de l’entité, Symfony hydrate automatiquement.

Champ non mappé → pas lié à l’entité, tu dois gérer sa valeur manuellement dans ton contrôleur ou via un event listener.
**utilité**
Ça permet d’ajouter des champs “temporaires” ou “techniques” dans un formulaire, qui ne correspondent pas directement à une propriété de l’entité.

Exemple typique :
- Champ “nouveau_groupe” → sert à créer un nouvel objet Groupe.
- Champ “mot_de_passe_confirmation” → sert à vérifier que l’utilisateur a bien tapé deux fois le même mot de passe, mais n’est pas stocké dans la base.
- Champ “captcha” → utilisé pour la sécurité, mais pas enregistré dans l’entité
-------------------------------------
Doctrine flush() 
La méthode flush() sur l'EntityManager (ou ObjectManager) exécute toutes les requêtes SQL en attente pour persister les entités en base de données, après les appels à persist(). Appelez-la une fois à la fin d'une séquence de créations/modifications pour optimiser les performances (ex. : dans fixtures ou contrôleurs Doctrine ORM).​

Utilisation pratique
php
$entity = new User();
$em->persist($entity);  // Marque pour insertion, sans requête SQL
$em->flush();           // Exécute INSERT/UPDATE/DELETE [web:49]
Dans vos fixtures Symfony : créez plusieurs entités, persist() chacune, puis un seul flush() final pour charger rapidement (ex. : php bin/console doctrine:fixtures:load).​

Bonnes pratiques
Évitez flush() après chaque persist() (ralentit) ; regroupez-les.​