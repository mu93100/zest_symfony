PROBLEME VE?NDREDI 16:50
Ton problème vient du mélange entre ces annotations sur l’entité et la config du formulaire plainPassword avec mapped => false.

Ce que font exactement ces propriétés
php
#[ORM\Column]
private ?string $password = null;

#[Assert\NotBlank(message: '[M E R C I  de renseigner ton mot de passe]')]
#[Assert\Length(min: 6, minMessage: '[M I N I M U M  {{ limit }} caractères]')]
private ?string $plainPassword = null;
password est la colonne en base (hashé, NOT NULL si ta colonne est définie comme ça).

plainPassword est un champ transient, utilisé pour la saisie + validation, puis vidé après hash.

Avec ces contraintes sur plainPassword, la validation de l’entité s’attend à ce que plainPassword soit renseigné à chaque fois qu’un User est validé.​

Problème avec mapped => false dans le formulaire
Dans ton RegistrationFormType tu as :

php
->add('plainPassword', PasswordType::class, [
    'mapped' => false,
    'attr' => ['autocomplete' => 'new-password'],
])
mapped => false signifie que Symfony ne copie jamais la valeur saisie dans $user->plainPassword.

Résultat : au moment où la validation d’entité se fait, plainPassword vaut toujours null → NotBlank déclenche une erreur, ou bien le hashage qui lit $user->getPlainPassword() ne voit rien.​

Deux solutions possibles (à choisir)
Option 1 : garder mapped => false (recommandé ici)
Enlever les contraintes d’assertion de l’entité sur plainPassword :

php
// Dans User.php
#[ORM\Column]
private ?string $password = null;

// SUPPRIMER les Assert ici
private ?string $plainPassword = null;
Mettre les contraintes sur le champ de formulaire dans RegistrationFormType :

php
use Symfony\Component\Validator\Constraints as Assert;

->add('plainPassword', PasswordType::class, [
    'mapped' => false,
    'attr' => ['autocomplete' => 'new-password'],
    'constraints' => [
        new Assert\NotBlank([
            'message' => '[M E R C I  de renseigner ton mot de passe]',
        ]),
        new Assert\Length([
            'min' => 6,
            'minMessage' => '[M I N I M U M  {{ limit }} caractères]',
        ]),
    ],
])
Dans le contrôleur, tu continues à faire :

php
$plainPassword = $form->get('plainPassword')->getData();
$user->setPassword(
    $userPasswordHasher->hashPassword($user, $plainPassword)
);
Cette approche évite tout conflit entre validation de l’entité et le fait que plainPassword n’est pas mappé.​

Option 2 : mapper plainPassword sur l’entité
Si tu veux garder les Assert sur l’entité :

Enlève mapped => false du champ de formulaire :

php
->add('plainPassword', PasswordType::class, [
    'attr' => ['autocomplete' => 'new-password'],
])
Symfony remplira automatiquement $user->plainPassword.

Tu peux alors hasher à partir de l’entité :

php
$plainPassword = $user->getPlainPassword();
$user->setPassword(
    $userPasswordHasher->hashPassword($user, $plainPassword)
);
$user->setPlainPassword(null);
Mais il faut être sûr que tu ne valides pas l’entité dans d’autres contextes où plainPassword doit rester optionnel (édition sans changer de mot de passe, par exemple), sinon NotBlank posera problème.​

Pour ton cas (formulaire d’enregistrement dédié), l’option 1 (contraintes dans le form, mapped => false, pas de Assert sur l’entité) est la plus simple et évite la plupart des erreurs de validation et de password NULL.










































Parfait Muriel 👌, alors voici comment tu peux ajouter la contrainte UniqueEntity directement dans ton entité User.php pour que Symfony valide automatiquement l’unicité de l’email avant même d’aller en base :

📌 Étapes
1. Importer l’annotation
En haut de ton fichier User.php, ajoute :

php
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
2. Ajouter l’attribut UniqueEntity à la classe
Juste au-dessus de la déclaration de ta classe User, ajoute :

php
#[UniqueEntity(
    fields: ['email'],
    message: 'Un compte existe déjà avec cet email.'
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    // ...
}
👉 Ici :

fields: ['email'] → indique que le champ email doit être unique.

message → c’est le message d’erreur qui sera affiché dans le formulaire si l’email est déjà pris.

3. Vérifier ton champ email
Tu dois avoir :

php
#[ORM\Column(length: 180, unique: true)]
private ?string $email = null;
👉 La contrainte unique: true en base reste indispensable pour la sécurité. Mais avec UniqueEntity, Symfony valide avant l’insertion et renvoie une erreur propre dans le formulaire.

✅ Résultat
Si quelqu’un saisit un email déjà existant → le formulaire sera invalide.

Tu n’auras plus besoin de vérifier manuellement dans ton contrôleur avec existsByEmail().

L’utilisateur verra directement le message d’erreur sous le champ email.
----------------------------------------

4. Côté EasyAdmin (CRUD)
Puisque tu es partie d’un make:admin:crud, tu peux :

Vérifier que ton UserCrudController affiche bien la relation groupe.

Vérifier que ton GroupeCrudController affiche bien la liste des membres.

Ajouter éventuellement des filtres (par ville, par groupe ouvert/fermé, etc.).

Personnaliser les labels pour que ce soit clair pour les admins.

5. Côté base de données
Lancer une migration Doctrine (php bin/console make:migration puis php bin/console doctrine:migrations:migrate) pour que les champs ajoutés (dateCreation, isOpen, etc.) soient bien en base.

Vérifier dans phpMyAdmin que la table groupe contient bien created_at, is_open, etc.

✨ Résumé
Il te reste principalement à :

Finaliser ton RegistrationController (logique métier).

Vérifier tes migrations Doctrine.

Personnaliser ton CRUD EasyAdmin pour que les admins voient bien les groupes et leurs membres.