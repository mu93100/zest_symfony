<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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
            IdField::new('id')->onlyOnDetail(), // proposé par IA mais pas natif symfony
            TextField::new('nom'),
            TextField::new('ville'),
            BooleanField::new('isReferent'),
            BooleanField::new('isOpen'),
            
            // Champ calculé : liste des membres
            TextField::new('membres')
                ->formatValue(function ($value, $entity) {
                    return implode(', ', $entity->getMembres()->map(fn($user) => $user->getNom())->toArray());
                })
                ->onlyOnDetail(),

            // Champs calculés pour afficher les infos référent (via User)
            // appellent getReferent() et affichent les infos du User référent 
            // (nom, email, téléphone).TextField::new('referentNom')
            TextField::new('referentNom')
                ->formatValue(function ($value, $entity) {
                    return $entity->getReferent()?->getNom() ?? '—';
                })
                ->onlyOnDetail(),

            TextField::new('referentEmail')
                ->formatValue(function ($value, $entity) {
                    return $entity->getReferent()?->getEmail() ?? '—';
                })
                ->onlyOnDetail(),

            TextField::new('referentTelephone')
                ->formatValue(function ($value, $entity) {
                    return $entity->getReferent()?->getTelephone() ?? '—';
                })
                ->onlyOnDetail(),
        ];
    }
}

// IA 14/12 pour affichage des membres et du référent :
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

// public function configureFields(string $pageName): iterable
// {
//     return [
//         TextField::new('nom', 'Nom du groupe'),

//         // Liste des membres
//         ArrayField::new('membres', 'Adhérents')
//             ->formatValue(function ($value, $entity) {
//                 $membres = $entity->getMembres();
//                 $liste = [];
//                 foreach ($membres as $membre) {
//                     $liste[] = sprintf('%s %s (%s)',
//                         $membre->getPrenom(),
//                         $membre->getNom(),
//                         $membre->getEmail()
//                     );
//                 }
//                 return implode('<br>', $liste);
//             }),

//         // Infos du référent
//         TextField::new('referentInfo', 'Référent')
//             ->formatValue(function ($value, $entity) {
//                 $referent = $entity->getReferent();
//                 if ($referent) {
//                     return sprintf('%s %s (%s)',
//                         $referent->getPrenom(),
//                         $referent->getNom(),
//                         $referent->getEmail()
//                     );
//                 }
//                 return 'Aucun référent';
//             }),
//     ];
// }

// OU PROPOSE PAR IA :  a voir après si je rajoute des IF
// Avec yield : tu écris chaque champ séparément, c’est plus flexible (tu peux mettre des conditions, des if, etc.).
// Avec return [...] : tu renvoies directement un tableau de champs, c’est plus compact.
// class GroupeCrudController extends AbstractCrudController
// {
//     public static function getEntityFqcn(): string
//     {
//         return Groupe::class;
//     }

//     public function configureFields(string $pageName): iterable
//     {
//         yield IdField::new('id')->onlyOnDetail();
//         yield TextField::new('nom');
//         yield TextField::new('ville');
//         yield BooleanField::new('isReferent');
//         yield BooleanField::new('isOpen');

//         // Champ calculé : liste des membres
//         yield TextField::new('membres')
//             ->formatValue(function ($value, $entity) {
//                 return implode(', ', $entity->getMembres()->map(fn($user) => $user->getNom())->toArray());
//             })
//             ->onlyOnDetail();

//         // Champs calculés pour afficher le référent (via User)
//         yield TextField::new('referent')
//             ->formatValue(function ($value, $entity) {
//                 return $entity->getReferent()?->getNom() ?? '—';
//             })
//             ->onlyOnDetail();

//         yield TextField::new('referentEmail')
//             ->formatValue(function ($value, $entity) {
//                 return $entity->getReferent()?->getEmail() ?? '—';
//             })
//             ->onlyOnDetail();

//         yield TextField::new('referentTelephone')
//             ->formatValue(function ($value, $entity) {
//                 return $entity->getReferent()?->getTelephone() ?? '—';
//             })
//             ->onlyOnDetail();
//     }
// }
