<?php

namespace App\Controller\Admin;

use App\Entity\Saison;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SaisonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Saison::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('nom')
            ->setLabel('Nom de la saison (ex : 2024/2025)');

        yield DateField::new('dateDebut')
            ->setLabel('Début de saison');

        yield DateField::new('dateFin')
            ->setLabel('Fin de saison');

        yield AssociationField::new('adhesions')
            ->setLabel('Adhésions')
            ->hideOnForm();
    }
}
