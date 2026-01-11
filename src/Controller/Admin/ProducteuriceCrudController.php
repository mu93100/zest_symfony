<?php

namespace App\Controller\Admin;

use App\Entity\Producteurice;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProducteuriceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Producteurice::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('nom', 'Nom du producteurice'),

            TextEditorField::new('description', 'Description')
                ->hideOnIndex(),

            ImageField::new('photo', 'Photo')
                ->setBasePath('uploads/producteurices')
                ->setUploadDir('public/uploads/producteurices')
                ->setRequired(false),

            AssociationField::new('produits', 'Produits')
                ->setFormTypeOptions(['by_reference' => false]),

            TextField::new('slug')
                ->onlyOnIndex(), // visible uniquement dans la liste admin
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $entityInstance->generateSlug();
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $entityInstance->generateSlug();
        }

        parent::updateEntity($em, $entityInstance);
    }
}
