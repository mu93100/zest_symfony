<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Controller\Admin\MediaCrudController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->hideOnForm(),

            TextField::new('nom', 'Nom du produit'),

            TextField::new('description', 'Description'),
            
            TextField::new('nomProducteurices', 'Producteurices')
                ->onlyOnIndex(), // voir les noms des prod dans INDEX : vue dashboard

            // ManyToMany Producteurices
            // AssociationField::new('producteurices', 'Producteurices')
            //     ->setFormTypeOption('autocomplete', false)
            //     ->setFormTypeOptions(['by_reference' => false]), // liste déroulante
            AssociationField::new('producteurices', 'Producteurices')// cases à cocher
                ->setFormTypeOption('expanded', true)
                ->setFormTypeOption('multiple', true)
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(), // pour affichage dans édit (modif)

            TextField::new('nomMedias', 'Médias')
                ->formatValue(function ($value, Produit $produit) {
                    $mediasProduit = $produit->getMedias()->filter(
                        fn(Media $m) => $m->getPage() === 'produit'
                    );

                    if ($mediasProduit->isEmpty()) {
                        return '—';
                    }

                    return implode(', ', $mediasProduit
                        ->map(fn(Media $m) => $m->getNomFichier())
                        ->toArray());
                })
                ->onlyOnIndex()
            ->renderAsHtml(),
    
            // OneToMany Medias
            CollectionField::new('medias', 'Photos / Fichiers')
                ->useEntryCrudForm(MediaCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Produit && method_exists($entityInstance, 'generateSlug')) {
            $entityInstance->generateSlug();
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Produit && method_exists($entityInstance, 'generateSlug')) {
            $entityInstance->generateSlug();
        }

        parent::updateEntity($em, $entityInstance);
    }
}
