<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('prenom'),
            TextField::new('nom'),
            EmailField::new('email'),
            TextField::new('telephone'),
            TextField::new('adresse'),
            TextField::new('codePostal'),
            TextField::new('ville'),
            DateField::new('dateDeNaissance'),
            IntegerField::new('nombreEnfants'),
            TextareaField::new('compositionFoyer'),
            AssociationField::new('groupe'),
            ChoiceField::new('roles')
                ->setLabel('Rôles')
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Contenus' => 'ROLE_CONTENU',
                    'Adhesions' => 'ROLE_ADHESION',
                    'Produits' => 'ROLE_PRODUIT',
                    'Poles' => 'ROLE_POLE',
                    'Utilisateur' => 'ROLE_USER',
                    'Vérifié' => 'ROLE_VERIFIED',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),
            TextField::new('plainPassword')
                ->setLabel('Mot de passe')
                ->setFormType(PasswordType::class)
                ->onlyOnForms()
                    ];
            // utilisation de  plainPassword et non pas password car on rentre un password non hashé
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User && $entityInstance->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $entityInstance, 
                $entityInstance->getPlainPassword()
            );
            $entityInstance->setPassword($hashedPassword);
            $entityInstance->setPlainPassword(null); // nettoie après hash
        }

        parent::persistEntity($entityManager, $entityInstance);
    } 
}
