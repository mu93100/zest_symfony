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
use App\Repository\UserRepository; 
use Doctrine\ORM\EntityRepository; 
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class GroupeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom du groupe'),
            TextField::new('adresseDistrib', 'Adresse de distribution'),
            TextField::new('ville', 'Ville'),
            BooleanField::new('isOpen', 'groupe OPEN')
                ->renderAsSwitch(false),

            // --- MEMBRES (INDEX)
            ArrayField::new('membres', 'Membres du groupe')
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
            
            // --- RÉFÉRENT (AFFICHAGE INDEX)
            TextField::new('referent', 'Référent')
                ->onlyOnIndex()
                ->formatValue(function ($value, Groupe $groupe) {
                    $user = $groupe->getReferent();
                    return $user
                        ? $user->getPrenom().' '.$user->getNom().' ('.$user->getEmail().' '.$user->getTelephone().')'
                        : '⚠️ aucun';
                }),

            // --- RÉFÉRENT (AFFICHAGE form)
            AssociationField::new('referent', 'Référent')
                ->onlyOnForms('edit')
                ->setFormTypeOptions([
                    'query_builder' => function (UserRepository $userRepository) {
                        $groupe = $this->getContext()->getEntity()->getInstance();
                        
                        return $userRepository->createQueryBuilder('u')
                            ->innerJoin('u.groupe', 'g')  // ⚠️ NOM DE TA RELATION User->Groupe
                            ->andWhere('g.id = :groupeId')
                            ->setParameter('groupeId', $groupe->getId());
                    },
                    'placeholder' => 'Aucun(e)',
                    'required' => false,
                ]),           
        ];
    }

        public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Groupes [ <span style="font-weight:lighter;font-size:0.5em">⚠️ la liste des membres se modifie dans la section Adhérents - impossible dans Groupes</span> ]');
    }
}
