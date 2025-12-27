<?php

namespace App\Controller\Admin;

use App\Entity\Adhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
            DateTimeField::new('dateAdhesion', 'Date d\'adhésion'),



            AssociationField::new('saison')
                ->setLabel('Saison')
                ->setRequired(true),
        // autres champs de Adhesion...
            AssociationField::new('user', 'Adhérent'),
            AssociationField::new('groupe', 'Groupe'),
            AssociationField::new('montantAdhesion', 'Montant'),
            // MoneyField::new('montantAdhesion.montant', 'Montant payé')
            //     ->setCurrency('EUR'), afficher le montant 
            BooleanField::new('paiement', 'Paiement validé'),

        ];


    }

}
