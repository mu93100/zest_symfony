<?php

namespace App\Controller\Admin;

use App\Entity\Recette;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
                ->hideOnIndex(),

            TextareaField::new('description', 'Description')
                ->hideOnIndex(),

            DateTimeField::new('datePublication', 'Date de publication')
                ->setFormTypeOptions([
                    'html5' => true,
                ])
                ->hideOnIndex(),

            // Relation ManyToMany avec Produit
            AssociationField::new('produit', 'Produits utilisés')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),

            // Relation ManyToOne avec User
            AssociationField::new('auteurice', 'Auteurice'),

            // Relation OneToMany avec Media
            CollectionField::new('medias', 'Photos')
                ->useEntryCrudForm(Media::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->onlyOnForms(),

            TextField::new('slug')
                ->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Recette) {

            // Si aucune date n'est définie → on met maintenant
            if (!$entityInstance->getDatePublication()) {
                $entityInstance->setDatePublication(new \DateTimeImmutable());
            }

            // Slug auto
            $entityInstance->generateSlug();
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Recette) {
            $entityInstance->generateSlug();
        }

        parent::updateEntity($em, $entityInstance);
    }
}
