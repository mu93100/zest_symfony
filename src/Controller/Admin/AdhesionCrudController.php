<?php

namespace App\Controller\Admin;

use App\Entity\Adhesion;
use App\Service\SaisonContext; // pour saison en cours
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Doctrine\ORM\EntityManagerInterface; //à supprimer NON
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;// pour export CSV (tableau/liste )
use EasyCorp\Bundle\EasyAdminBundle\Config\Action; 
// filtrage par saison
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto; 
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto; 
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection; 
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;

class AdhesionCrudController extends AbstractCrudController
{
    public function __construct(
        private SaisonContext $saisonContext // pour injecter saison en cours
    ) {}

    private function getSaisonCourante(): Saison // pour saison obligatoire (au moins 1 saison séléctionnée)
    {
        $saison = $this->saisonContext->getSaison();

        if (!$saison) {
            throw new \LogicException("[ ⚠️ aucune saison sélectionnée  ]");
        }

        return $saison;
    }

    public static function getEntityFqcn(): string
    {
        return Adhesion::class;
    }   
        // public function configureTemplates(Templates $templates): Templates
        // {
        //     // return $templates->addTemplate('layout', 'admin/layout.html.twig');
        //     return $templates
        //         ->addTemplate('layout', 'admin/easyadmin_layout.html.twig')
        //         ->addTemplate('field/produits', 'admin/fields/produits_flex_row.html.twig');
        // }
    /** * Filtre automatique : n’affiche que les adhésions de la saison choisie */ 
    
    public function createIndexQueryBuilder( // pour filtrer les adhésions par saison 
        SearchDto $searchDto, 
        EntityDto $entityDto, 
        FieldCollection $fields, 
        FilterCollection $filters 
    ): QueryBuilder { 
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters); 

        $saison = $this->saisonContext->getSaison(); 

        if (!$saison) { 
            throw new \LogicException("[ ⚠️ aucune saison sélectionnée ]"); } 
        return $qb ->andWhere('entity.saison = :saison') ->setParameter('saison', $saison); 
    }


    // --------- titre + saison / selecteur de saison géré dans templates/admin/adhesion_index.html.twig
    public function configureCrud(Crud $crud): Crud
    {
        $saison = $this->saisonContext->getSaison();
        $nomSaison = $saison ? $saison->getNom() : '—';

        return $crud
            // ->setPageTitle(Crud::PAGE_INDEX, 'Adhésions')
            // ->setPageTitle(
            //     Crud::PAGE_INDEX,
            //     'Adhésions ' . $nomSaison
            // )
            ->overrideTemplate('crud/index', 'admin/adhesion_index.html.twig');
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        $responseParameters->set('saisons', $this->saisonContext->getAll());
        $responseParameters->set('saisonEnCours', $this->saisonContext->getSaison());

        return $responseParameters;
    }


    //  -------- buttons EXPORTER (CVS = tableau) avec fichier ExportController.php
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::new('export_groupes', 'Exporter Groupes')
                ->linkToRoute('export_groupes')
                ->createAsGlobalAction())                

            ->add(Crud::PAGE_INDEX, Action::new('export_mails_membres', 'Exporter mails membres')
                ->linkToRoute('export_mails_membres')
                ->createAsGlobalAction())

            ->add(Crud::PAGE_INDEX, Action::new('export_mails_referents', 'Exporter mails référents')
                ->linkToRoute('export_mails_referents')
                ->createAsGlobalAction());
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            DateTimeField::new('dateAdhesion', 'Date d\'adhésion'),
            AssociationField::new('user', 'Adhérent'),
            AssociationField::new('groupe', 'Groupe'),
            // BooleanField::new('isReferent')
            //     ->renderAsSwitch()
            //     ->setLabel('Référent·e saison en cours'),

            AssociationField::new('saison')
                ->setLabel('Saison')
                ->setRequired(true),
            
            AssociationField::new('montantAdhesion', 'Montant'),
            
            IntegerField::new('montantPaiementLibre', 'Montant libre')
                ->formatValue(function ($value) {
                    return $value ? $value . ' €' : '—';
                })
                ->onlyOnIndex(),
                            
            BooleanField::new('paiementValide', 'Paiement OK')
                ->onlyOnIndex()
                ->renderAsSwitch(false), // rajout pour lecture seule dans index + modif dans form
            BooleanField::new('paiementValide', 'Paiement OK')
                ->onlyOnForms(),

            // IntegerField::new('montantPaiementLibre', 'Montant libre (€)')
            //     ->setFormTypeOptions([
            //         'attr' => ['min' => 0],
            //         'empty_data' => null,
            // 'required' => false, 
            //         'help' => 'Montant personnalisé en euros (optionnel)'
            //     ])
            //     ->onlyOnForms(),

            // ✅ Liste déroulante ASSOCIÉE (pas IntegerField)
            // AssociationField::new('montantAdhesion', 'Montant pré-défini')
            //     ->setFormTypeOptions([
            //         'by_reference' => false,
            //         'choice_label' => 'montant',  // ← Affiche libelle
            //         'required' => false
            //     ])
            //     // ->autocomplete()  // ← Liste déroulante fluide
            //     ->onlyOnForms(),
            //         AssociationField::new('montantAdhesion', 'Montant pré-défini')
            // ->setFormTypeOptions(['by_reference' => false, 'required' => false])
            // ->onlyOnForms(),
    
            AssociationField::new('montantAdhesion', 'Montant choisi')
            ->setFormTypeOptions([
                'choice_label' => 'montant',  // Nom de la propriété à afficher
                'required' => false
            ])
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
    
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Logique isReferent ici avec $entityInstance->getUser(), $entityInstance->getGroupe()
        parent::persistEntity($entityManager, $entityInstance);
    }
}
