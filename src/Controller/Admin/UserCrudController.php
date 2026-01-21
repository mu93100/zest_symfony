<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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
            IdField::new('id')->hideOnForm(),
            TextField::new('prenom'),
            TextField::new('nom'),
            EmailField::new('email'),
            TextField::new('telephone'),
            AssociationField::new('groupe'),

            AssociationField::new('referent', 'Référent')
                // ->onlyOnIndex()
                ->formatValue(function ($user) {
                    return $user 
                        ? $user->getPrenom() . ' ' . $user->getNom() . ' (' . $user->getEmail() . ' ' . $user->getTelephone() .')'
                        : 'Aucun';
                }),

            // BooleanField::new('isReferent')
            //     ->renderAsSwitch(false)
            //     ->onlyOnIndex(),
            // BooleanField::new('isReferent')
            // ->onlyOnForms(),
            AssociationField::new('referent', 'Référent')
                ->onlyOnIndex()
                ->formatValue(function ($user) {
                    return $user 
                        ? $user->getPrenom() . ' ' . $user->getNom() . ' (' . $user->getEmail() . ')'
                        : 'Aucun';
                }),

            ChoiceField::new('roles')
                ->setLabel('Rôles')
                ->setChoices([
                    'Admin' => 'ROLE_ADMIN',
                    'Contenus' => 'ROLE_CONTENU',
                    'Adhesions' => 'ROLE_ADHESION',
                    'Produits' => 'ROLE_PRODUIT',
                    'Poles' => 'ROLE_POLE',
                    'Utilisateur' => 'ROLE_USER',
                    'Vérifié' => 'ROLE_VERIFIED',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),

            TextField::new('adresse'),
            TextField::new('codePostal'),
            TextField::new('ville'),
            DateField::new('dateDeNaissance'),
            IntegerField::new('nombreEnfants')
            ->setFormTypeOptions([
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'step' => 1,
                ]
            ]),
            IntegerField::new('compositionFoyer')
            ->setFormTypeOptions([
                'required' => false,
                'attr' => [
                    'min' => 1,
                    'step' => 1,
                ]
            ]),

            TextField::new('plainPassword') // utilisation de  plainPassword et non pas password car on rentre un password non hashé
                ->setLabel('Mot de passe')
                ->setFormType(PasswordType::class)
                ->onlyOnForms()
        ];
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
