<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\JsonField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('prenom'),
            TextField::new('nom'),
            EmailField::new('email'),
            TextField::new('telephone'),
            TextField::new('adresse'),
            IntegerField::new('codePostal'),
            TextField::new('ville'),
            DateField::new('dateDeNaissance'),
            IntegerField::new('nombreEnfants'),
            TextareaField::new('compositionFoyer'),
            TextareaField::new('motivationsAttentes'),
            TextareaField::new('competences'),
            BooleanField::new('isAdmin')->setLabel('Admin ?'),
            BooleanField::new('isVerified')->setLabel('Vérifié ?'),
            JsonField::new('roles'),
            AssociationField::new('groupe'),
            AssociationField::new('adhesion'),
            AssociationField::new('participationDispo'),
        ];
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
