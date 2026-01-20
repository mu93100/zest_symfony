<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class GroupeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }
// rajout IA / pas nécessaire / a voir
    // public function configureCrud(Crud $crud): Crud
    // {
    //     return $crud
    //         ->setEntityLabelInSingular('Groupe')
    //         ->setEntityLabelInPlural('Groupes')
    //         ->setDefaultSort(['nom' => 'ASC']);
    // }
// FIN rajout IA  
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom du groupe'),
            TextField::new('adresseDistrib', 'Adresse de distribution'),
            TextField::new('ville', 'Ville'),
            BooleanField::new('isOpen', 'groupe OPEN'),
//         yield BooleanField::new('isOpen');

            // Liste des membres 
            ArrayField::new('membres', 'Adhérents')
                ->formatValue(function ($value, $entity) {
                    return implode('<br>', $entity->getMembres()->map(
                        fn($user) => sprintf('%s %s (%s, %s)', 
                            $user->getPrenom(), 
                            $user->getNom(), 
                            $user->getEmail(),
                            $user->getTelephone()
                        )
                    )->toArray());
                }),
            

            //  count des membres d'un groupe
            AssociationField::new('membres', 'Nb membres')
                ->onlyOnIndex(),

            // // 2. Les détails du référent (virtuel)
            // TextField::new('referentInfo', 'Référent')
            //     ->setVirtual(true)
            //     ->onlyOnIndex(),
                        
            // Dans GroupeCrudController
            AssociationField::new('referent', 'Référent')
                // ->onlyOnIndex()
                ->formatValue(function ($user) {
                    return $user 
                        ? $user->getPrenom() . ' ' . $user->getNom() . ' (' . $user->getEmail() . ' ' . $user->getTelephone() .')'
                        : 'Aucun';
                }),
                
            // OU
            // AssociationField::new('referent', 'Référent')
            //     ->onlyOnIndex()
            //     ->formatValue(function ($user) {
            //         return $user 
            //             ? sprintf('%s %s (%s)', 
            //                 $user->getPrenom(), 
            //                 $user->getNom(), 
            //                 $user->getEmail()
            //             ) 
            //             : 'Aucun';
            //     }),
            

        ];
    }
}
