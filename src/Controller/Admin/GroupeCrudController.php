<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class GroupeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('ville'),
            BooleanField::new('isReferent'),
            TextField::new('referentNom'),
            TextField::new('referentEmail'),
            TextField::new('referentTelephone'),
            BooleanField::new('isGroupeOpen'),

            // Champ calculé : liste des membres
            TextField::new('membres')
                ->formatValue(function ($value, $entity) {
                    return implode(', ', $entity->getMembres()->map(fn($user) => $user->getNom())->toArray());
                })
                ->onlyOnDetail(), // affiché uniquement dans la vue détail
        ];
    }
}
