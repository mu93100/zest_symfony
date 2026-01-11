<?php
namespace App\Controller\Admin;

use App\Entity\Recette;
use App\Controller\Admin\MediaCrudController;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recette::class;
    }
public function configureFields(string $pageName): iterable
{
    return [

        IdField::new('id')->hideOnForm(),

        TextField::new('titre', 'Titre'),

        IntegerField::new('nombreMangeurs', 'Nombre de mangeurs'),

        TextareaField::new('ingredients', 'Ingrédients')
            ->onlyOnIndex(),

        TextareaField::new('description', 'Description')
            ->onlyOnIndex(),

        // 👉 Affichage dans l’index (liste)
        AssociationField::new('produit', 'Produits utilisés')
            ->formatValue(function ($value, $entity) {
                return implode(
                    ', ',
                    $entity->getProduit()
                        ->map(fn($p) => $p->__toString())
                        ->toArray()
                );
            })
            ->onlyOnIndex(),

        // 👉 Champ pour le formulaire
        AssociationField::new('produit', 'Produits utilisés')
            ->setFormTypeOptions(['by_reference' => false])
            ->onlyOnForms(),

        AssociationField::new('auteurice', 'Auteurice'),

        CollectionField::new('medias', 'Photos / Fichiers')
            ->useEntryCrudForm(MediaCrudController::class)
            ->setFormTypeOptions(['by_reference' => false])
            ->onlyOnForms(),
    ];
}

    // public function configureFields(string $pageName): iterable
    // {
    //     return [

    //         IdField::new('id')->hideOnForm(),

    //         TextField::new('titre', 'Titre'),

    //         IntegerField::new('nombreMangeurs', 'Nombre de mangeurs'),

    //         TextareaField::new('ingredients', 'Ingrédients'),
    //             // ->onlyOnIndex(),

    //         TextareaField::new('description', 'Description'),
    //             // ->onlyOnIndex(),

    //         // ManyToMany avec produit
    //         AssociationField::new('produit', 'Produits utilisés')
    //             // ->setFormTypeOptions(['by_reference' => false]),
    //             ->formatValue(function ($value, $entity) { 
    //                 return $entity->getProduit()
    //                 ->map(fn($p) => $p->__toString()) 
    //                 ->join(', ');
    //             }) 
    //             ->onlyOnIndex(),
    //         // ManyToOne avec user
    //         AssociationField::new('auteurice', 'Auteurice'),

    //         // OneToMany avec Media
    //         CollectionField::new('medias', 'Photos / Fichiers')
    //             ->useEntryCrudForm(MediaCrudController::class)
    //             ->setFormTypeOptions(['by_reference' => false])
    //             ->onlyOnForms(),
    //     ];
    // }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Recette) {

            // date automatique comme l'ID
            if (!$entityInstance->getDatePublication()) {
                $entityInstance->setDatePublication(new \DateTimeImmutable());
            }
        }

        parent::persistEntity($em, $entityInstance);
    }
}
