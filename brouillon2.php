<?php

namespace App\Controller\Admin;

use App\Entity\Adhesion;
use App\Entity\Saison;
use App\Entity\User;
use App\Entity\Groupe;
use App\Entity\MontantAdhesion;
use App\Entity\Motivation;
use App\Entity\Dispo;
use App\Entity\Pole;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\HttpFoundation\Response;  //à supprimer
// use EasyCorp\Bundle\EasyAdminBundle\Config\Templates;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;  //à supprimer
use Symfony\Component\HttpFoundation\Request; //à supprimer
use Doctrine\ORM\EntityManagerInterface; //à supprimer NON
use EasyCorp\Bundle\EasyAdminBundle\Config\Templates;
use App\Repository\SaisonRepository;
use App\Repository\AdhesionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;



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
            
            DateTimeField::new('dateAdhesion', 'Date d\'adhésion'),
            AssociationField::new('user', 'Adhérent'),
            AssociationField::new('groupe', 'Groupe'),
            AssociationField::new('saison')
                ->setLabel('Saison')
                ->setRequired(true),
            
            
            BooleanField::new('paiementValide', 'Paiement effectué')->hideOnForm(),
            // integerField::new('paiement', 'Montant libre'),
            TextField::new('montantPaiementLibre', 'Montant libre')
                ->formatValue(function ($value) {
                    return $value ? $value . '€' : '—';
                })
                ->onlyOnIndex(),

                AssociationField::new('montantAdhesion', 'Montant pré-défini')
    ->setFormTypeOptions(['by_reference' => false, 'required' => false])
    ->onlyOnForms(),
    
            // ✅ + Montant libre (input numérique)
            IntegerField::new('montantPaiementLibre', 'OU Montant libre (€)')
                ->setFormTypeOptions([
                    'attr' => ['min' => 0],
                    'empty_data' => null,
                    'required' => false
                ])
                ->onlyOnForms(),
        ];
    }
}
