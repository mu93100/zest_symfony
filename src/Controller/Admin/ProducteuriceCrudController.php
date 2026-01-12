<?php

namespace App\Controller\Admin;

use App\Entity\Producteurice;
use App\Entity\Produit;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\FileType;use EasyCorp\Bundle\EasyAdminBundle\Field\Field; 
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

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

            TextField::new('nom', 'Nom'),

            BooleanField::new('isCoop', 'Coopérative ?'),

            TextField::new('site', 'Site web'),

            TextField::new('lienProduits', 'Lien vers les produits'),

            TextEditorField::new('description', 'Description')
                ->hideOnIndex(), // description visible uniquement en édition

            ImageField::new('logoMedia', 'Logo') 
                ->setBasePath('uploads/medias') 
                ->formatValue(function ($value, Producteurice $p) { 
                    $logo = $p->getLogoMedia(); 
                    return $logo ? $logo->getNomFichier() : null; 
                }) 
                ->onlyOnIndex(),

            TextField::new('nomMedias', 'Médias')
                ->onlyOnIndex(),

            Field::new('photos')
                ->setFormType(FileType::class)
                ->setFormTypeOptions([
                    'multiple' => true,
                    'required' => false,
                ])
                ->setLabel('Photos'),
            
                
            // produit : voir les noms dans INDEX/ vue dashboard
            TextField::new('nomProduits', 'Produits')
                ->onlyOnIndex(), 

            // Produit : voir les cases à cocher dans edit
            AssociationField::new('produits', 'Produits') // cases à cocher
                ->setFormTypeOptions([ 
                    'expanded' => true, 
                    'multiple' => true, 
                    'by_reference' => false, 
                ])
                ->onlyOnForms(),

            TextField::new('slug')
                ->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $this->handleUploads($entityInstance);
            $entityInstance->generateSlug();
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $this->handleUploads($entityInstance);
            $entityInstance->generateSlug();
        }

        parent::updateEntity($em, $entityInstance);
    }

    private function handleUploads(Producteurice $producteurice): void
    {
        foreach ($producteurice->getPhotos() as $uploadedFile) {
            if ($uploadedFile === null) {
                continue;
            }

            $media = new Media();
            $media->setFile($uploadedFile);
            $media->setProducteurice($producteurice);
            $media->setRole('photo_supplementaire');

            $producteurice->addMedia($media);
        }

        $producteurice->setPhotos([]);
    }
}
