<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use App\Entity\User;
use App\Entity\Saison;
use App\Entity\GroupeReferentSaison;
use App\Service\SaisonContext; // pour saison en cours
use App\Repository\UserRepository; 
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;// pour export CSV (tableau/liste )
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore; // pour selecteur de saison dans titre


class GroupeCrudController extends AbstractCrudController
{
    public function __construct(
        private SaisonContext $saisonContext
    ) {}

    private function getSaisonCourante(): Saison
    {
        $saison = $this->saisonContext->getSaison();

        if (!$saison) {
            throw new \LogicException("[ ⚠️ aucune saison sélectionnée  ]");
        }
        return $saison;
    }

    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }


    // --------- titre + saison 
    public function configureCrud(Crud $crud): Crud
    {
        $saison = $this->saisonContext->getSaison();
        $nomSaison = $saison ? $saison->getNom() : '—';

        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Adhésions')
            ->setPageTitle(
                Crud::PAGE_INDEX,
                'Adhésions ' . $nomSaison
            );
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        $responseParameters->set('saisons', $this->saisonContext->getAll());
        $responseParameters->set('saisonEnCours', $this->saisonContext->getSaison());

        return $responseParameters;
    }
    
    // --------- ajout buttons EXPORTER (CVS = tableau) avec fichier ExportController.php
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
            TextField::new('nom', 'Nom du groupe'),
            TextField::new('adresseDistrib', 'Adresse de distribution'),
            TextField::new('codePostal', 'Code postal'),
            TextField::new('ville', 'Ville'),
            BooleanField::new('isOpen', 'groupe OPEN')
                ->onlyOnIndex()
                ->renderAsSwitch(false), // rajout pour lecture seule dans index + modif dans form
            BooleanField::new('isOpen', 'groupe OPEN')
                ->onlyOnForms(),

            // --------- membres du groupe INDEX
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

            // --------- count des membres d'un groupe
            AssociationField::new('membres', 'Nb membres')
                ->onlyOnIndex(),
            
            // --------- référent INDEX avec entitée pivot GroupeReferentSaison
            TextField::new('nom', 'Référent (saison active)')
                ->onlyOnIndex()
                ->formatValue(function ($value, Groupe $groupe) {
                    $saison = $this->saisonContext->getSaison();
                
                    if (!$saison) {
                        return '⚠️ aucune saison';
                    }
                
                    $pivot = $groupe->getGroupeReferentSaisons()
                        ->filter(fn($grs) => $grs->getSaison() === $saison)
                        ->first();
                
                    if (!$pivot || !$pivot->getReferent()) {
                        return '⚠️ aucun référent';
                    }
                
                    $u = $pivot->getReferent();
                    return sprintf(
                        '%s %s (%s %s)',
                        $u->getPrenom(),
                        $u->getNom(),
                        $u->getEmail(),
                        $u->getTelephone()
                    );
                })
                ->setSortable(false),


            // ----------- référent FORM (champ virtuel)
            ChoiceField::new('changerReferent', 'Référent (saison active)')
                ->onlyOnForms()
                ->setChoices(function (Groupe $groupe) {
                    $choices = [];
                    foreach ($groupe->getMembres() as $membre) {
                        $choices[$membre->getPrenom().' '.$membre->getNom()] = $membre->getId();
                    }
                    return $choices;
                })
                ->setRequired(true) // ← OBLIGATOIRE
                ->setFormTypeOption('mapped', false),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        $this->saveReferent($em, $entityInstance);
        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        $this->saveReferent($em, $entityInstance);
        parent::updateEntity($em, $entityInstance);
    }

    private function saveReferent(EntityManagerInterface $em, Groupe $groupe): void
    {
        $saison = $this->getSaisonCourante();

        $request = $this->getContext()->getRequest();
        $data = $request->get('Groupe');
        $referentId = $data['changerReferent'] ?? null;

        if (!$referentId) { 
            throw new \LogicException("Un groupe doit toujours avoir un référent pour la saison active."); 
        } 
        $pivot = $em->getRepository(GroupeReferentSaison::class) 
            ->findOneBy(['groupe' => $groupe, 'saison' => $saison]); 
            
        if (!$pivot) { 
            $pivot = new GroupeReferentSaison(); 
            $pivot->setGroupe($groupe); 
            $pivot->setSaison($saison); 
            $em->persist($pivot); 
        } 
        $user = $em->getRepository(User::class)->find($referentId); 
        $pivot->setReferent($user);
    }

    public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    {
        $saison = $this->getSaisonCourante();

        $adhesions = $entityInstance->getAdhesions()->filter(
            fn($a) => $a->getSaison() === $saison
        );

        if (count($adhesions) > 0) {
            $this->addFlash('warning','[ ⚠️ Impossible de supprimer ce groupe : il a des adhésions pour la saison en cours ]');
            return;
        }

        parent::deleteEntity($em, $entityInstance);
    }
}

