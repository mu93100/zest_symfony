<?php

namespace App\Controller\Admin;

use App\Entity\GroupeReferentSaison;
use App\Entity\Groupe;
use App\Entity\User;
use App\Entity\Saison;
use App\Service\SaisonContext;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class GroupeReferentSaisonCrudController extends AbstractCrudController
{
    public function __construct(
        private SaisonContext $saisonContext,
    ) {}

    public static function getEntityFqcn(): string
    {
        return GroupeReferentSaison::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $saison = $this->saisonContext->getSaison();
        $nomSaison = $saison ? $saison->getNom() : '—';

        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Référents de groupes ' . $nomSaison)
            ->setEntityLabelInSingular('Référent de groupe')
            ->setEntityLabelInPlural('Référents de groupes');
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $saison = $this->saisonContext->getSaison();
        if (!$saison) {
            throw new \LogicException('[ ⚠️ aucune saison sélectionnée ]');
        }

        return $qb
            ->andWhere('entity.saison = :saison')
            ->setParameter('saison', $saison);
    }

    public function configureFields(string $pageName): iterable
    {
        $saison = $this->saisonContext->getSaison();

        // Groupe
        yield AssociationField::new('groupe')
            ->setLabel('Groupe')
            ->setFormTypeOption('choice_label', 'nom');

        // Référent (User)
        yield AssociationField::new('referent')
            ->setLabel('Référent')
            ->setFormTypeOption('choice_label', 'nom');

        // Saison : auto-remplie, non modifiable en formulaire, cachée en index
        $saisonField = AssociationField::new('saison')
            ->setLabel('Saison');

        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $saisonField
                ->setFormTypeOption('data', $saison)
                ->setDisabled(true);
        } else {
            $saisonField->hideOnIndex();
        }

        yield $saisonField;
    }
}
