<?php

// namespace App\Controller\Admin;

// use App\Entity\Groupe;
// use App\Entity\User;
// use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
// use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
// use App\Repository\UserRepository;

// class GroupeCrudController extends AbstractCrudController
// {
//     public static function getEntityFqcn(): string
//     {
//         return Groupe::class;
//     }

//     public function configureFields(string $pageName): iterable
// {
//     return [
//         IdField::new('id')->hideOnForm(),

//         TextField::new('nom', 'Nom du groupe'),
//         TextField::new('adresseDistrib', 'Adresse de distribution'),
//         TextField::new('ville', 'Ville'),
//         BooleanField::new('isOpen', 'Groupe OPEN'),

//         // --- LISTE DES MEMBRES (CHAMP VIRTUEL, NE TOUCHE PAS À "membres")
//         TextField::new('membresList', 'Adhérents')
//             ->onlyOnIndex()
//             ->formatValue(function ($value, Groupe $groupe) {
//                 return implode("\n", $groupe->getMembres()->map(
//                     fn(User $user) => sprintf(
//                         '%s %s (%s, %s)',
//                         $user->getPrenom(),
//                         $user->getNom(),
//                         $user->getEmail(),
//                         $user->getTelephone()
//                     )
//                 )->toArray());
//             }),

//         // --- RÉFÉRENT (FORMULAIRE) : UNIQUEMENT LES MEMBRES DU GROUPE
//         AssociationField::new('referent', 'Référent')
//             ->onlyOnForms()
//             ->setFormTypeOption('query_builder', function (UserRepository $repo) {
//                 $groupe = $this->getContext()->getEntity()->getInstance();
//                 return $repo->createQueryBuilder('u')
//                     ->andWhere('u.groupe = :groupe')
//                     ->setParameter('groupe', $groupe);
//             })
//             ->setFormTypeOption('placeholder', 'Aucun(e)'),

//         // --- NB MEMBRES (CHAMP VIRTUEL)
//         IntegerField::new('membresCount', 'Nb membres')
//             ->onlyOnIndex()
//             ->formatValue(fn ($v, Groupe $g) => $g->getMembres()->count()),

//         // --- RÉFÉRENT (AFFICHAGE INDEX)
//         TextField::new('referentInfo', 'Référent')
//             ->onlyOnIndex()
//             ->formatValue(function ($value, Groupe $groupe) {
//                 $user = $groupe->getReferent();
//                 return $user
//                     ? $user->getPrenom().' '.$user->getNom().' ('.$user->getEmail().' '.$user->getTelephone().')'
//                     : 'Aucun';
//             }),
//     ];
// }

// }

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
// use EasyCorp\Bundle\EasyAdminBundle\Doctrine\ORM\QueryBuilder;
use App\Repository\UserRepository; 
use Doctrine\ORM\EntityRepository; 
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;



class GroupeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }

//     public function configureFields(string $pageName): iterable
//     {
//         return [
//             IdField::new('id')->hideOnForm(),
//             TextField::new('nom', 'Nom du groupe'),
//             TextField::new('adresseDistrib', 'Adresse de distribution'),
//             TextField::new('ville', 'Ville'),
//             BooleanField::new('isOpen', 'groupe OPEN'),

//             // Liste des membres 
//             ArrayField::new('membres', 'Adhérents')
//                 ->formatValue(function ($value, $entity) {
//                     return implode('<br>', $entity->getMembres()->map(
//                         fn($user) => sprintf('%s %s (%s, %s)', 
//                             $user->getPrenom(), 
//                             $user->getNom(), 
//                             $user->getEmail(),
//                             $user->getTelephone()
//                         )
//                     )->toArray());
//                 }),
            
                
                
//             // -----------
//             // Champ RÉFÉRENT (formulaire) — filtré sur les membres du groupe 
//             AssociationField::new('referent', 'Référent') 
//                 ->onlyOnForms() 
//                 ->setFormTypeOption('query_builder', function (UserRepository $userRepository) { 
//                     $groupe = $this->getContext()->getEntity()->getInstance(); 
//                     return $userRepository->createQueryBuilder('u') 
//                         ->andWhere('u.groupe = :groupe') 
//                         ->setParameter('groupe', $groupe); 
//                 }) 
//                 ->setFormTypeOption('placeholder', 'AucuUUUn(e)'), 

//                 // Nombre de membres (champ virtuel) 
//             IntegerField::new('membresCount', 'Nb membres')
//                 ->onlyOnIndex() 
//                 ->formatValue(fn ($v, Groupe $g) => $g->getMembres()->count()), 

//             // Affichage du référent en index (champ virtuel) 
//             TextField::new('referentInfo', 'Référent')
//                 ->onlyOnIndex()
//                 ->formatValue(function ($value, Groupe $groupe) { 
//                     $user = $groupe->getReferent(); 
//                     return $user ? $user->getPrenom().' '.$user->getNom().' ('.$user->getEmail().' '.$user->getTelephone().')' : 'Aucun'; 
//                 }),
//             // -----------
//             // Dans configureFields()
// // AssociationField::new('referent', 'Référent')
// //     ->onlyOnForms()
// //     ->setFormTypeOption('query_builder', function (UserRepository $userRepository) {
// //         $groupe = $this->getContext()->getEntity()->getInstance();

// //         return $userRepository->createQueryBuilder('u')
// //             ->andWhere('u.groupe = :groupe')
// //             ->setParameter('groupe', $groupe);
// //     })
// //     ->setFormTypeOption('placeholder', 'Aucun(e)'),



//             // AssociationField::new('referent', 'Référent')
//             //     ->onlyOnForms()
//             //     ->setQueryBuilder(
//             //         fn (QueryBuilder $queryBuilder) => $queryBuilder
//             //             ->andWhere('u.groupeReferent = :groupeReferent')  // Supposant que User a $groupe
//             //             ->setParameter('groupeReferent', $this->getContext()->getEntity()->getInstance())
//             //     ),
//             //  count des membres d'un groupe
//             // AssociationField::new('membres', 'Nb membres')
//             //     ->onlyOnIndex(),
//             // IntegerField::new('membresCount', 'Nb membres')
//             //     ->onlyOnIndex()
//             //     ->formatValue(fn ($v, Groupe $g) => $g->getMembres()->count()),

//             // // 2. Les détails du référent (virtuel)
//             // TextField::new('referentInfo', 'Référent')
//             //     ->setVirtual(true)
//             //     ->onlyOnIndex(),
                        
//             // AssociationField::new('referent', 'Référent')
//             //     ->onlyOnIndex()
//             //     ->formatValue(function ($user) {
//             //         return $user 
//             //             ? $user->getPrenom() . ' ' . $user->getNom() . ' (' . $user->getEmail() . ' ' . $user->getTelephone() .')'
//             //             : 'Aucun';
//             //     }),
            

//         ];
//     }

public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),
        TextField::new('nom', 'Nom du groupe'),
        TextField::new('adresseDistrib', 'Adresse de distribution'),
        TextField::new('ville', 'Ville'),
        BooleanField::new('isOpen', 'groupe OPEN'),

        // Liste des membres
        ArrayField::new('membres', 'Adhérents')
            ->formatValue(function ($value, Groupe $groupe) {
                return implode('<br>', $groupe->getMembres()->map(
                    fn(User $user) => sprintf(
                        '%s %s (%s, %s)',
                        $user->getPrenom(),
                        $user->getNom(),
                        $user->getEmail(),
                        $user->getTelephone()
                    )
                )->toArray());
            }),

        // Champ RÉFÉRENT (formulaire) — filtré sur les membres du groupe
        AssociationField::new('referent', 'Référent')
            ->onlyOnForms()
            ->setFormTypeOption('query_builder', function (UserRepository $repo) {
                $groupe = $this->getContext()->getEntity()->getInstance();
                return $repo->createQueryBuilder('u')
                    ->andWhere('u.groupe = :groupe')
                    ->setParameter('groupe', $groupe);
            })
            ->setFormTypeOption('placeholder', 'Aucun(e)'),

        // Nombre de membres (champ virtuel)
        IntegerField::new('membresCount', 'Nb membres')
            ->onlyOnIndex()
            ->formatValue(fn ($v, Groupe $g) => $g->getMembres()->count()),

        // Référent affiché dans l’index (champ virtuel)
        TextField::new('referentInfo', 'Référent')
            ->onlyOnIndex()
            ->formatValue(function ($value, Groupe $groupe) {
                $user = $groupe->getReferent();
                return $user
                    ? $user->getPrenom().' '.$user->getNom().' ('.$user->getEmail().' '.$user->getTelephone().')'
                    : 'Aucun';
            }),
    ];
}
}








        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('nom', 'Nom du groupe'),
            TextField::new('adresseDistrib', 'Adresse de distribution'),
            TextField::new('ville', 'Ville'),
            BooleanField::new('isOpen', 'groupe OPEN'),

            // --- LISTE DES MEMBRES (INDEX)
            ArrayField::new('membres', 'Adhérents')
                ->onlyOnIndex()
                ->formatValue(function ($value, Groupe $groupe) {
                    return implode('<br>', $groupe->getMembres()->map(
                        fn(User $user) => sprintf(
                            '%s %s (%s, %s)',
                            $user->getPrenom(),
                            $user->getNom(),
                            $user->getEmail(),
                            $user->getTelephone()
                        )
                    )->toArray());
                }),

            // --- RÉFÉRENT (FORMULAIRE) : seulement les membres du groupe
            AssociationField::new('referent', 'Référent')
                ->onlyOnForms()
                ->setFormTypeOptions([
                    'query_builder' => function (UserRepository $userRepository) {
                        /** @var Groupe $groupe */
                        $groupe = $this->getContext()->getEntity()->getInstance();

                        return $userRepository->createQueryBuilder('u')
                            ->andWhere('u.groupe = :groupe')      // relation membre -> groupe
                            ->setParameter('groupe', $groupe);
                    },
                    'placeholder' => 'Aucun(e)',
                    'required' => false,
                ]),

            // --- RÉFÉRENT (AFFICHAGE INDEX)
            TextField::new('referent', 'Référent')
                ->onlyOnIndex()
                ->formatValue(function ($value, Groupe $groupe) {
                    $user = $groupe->getReferent();
                    return $user
                        ? $user->getPrenom().' '.$user->getNom().' ('.$user->getEmail().' '.$user->getTelephone().')'
                        : '--';
                }),
        ];
    

