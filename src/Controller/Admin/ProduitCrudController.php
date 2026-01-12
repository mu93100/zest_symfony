<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\Media;
use App\Controller\Admin\MediaCrudController;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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

            TextField::new('nom', 'Nom'),

            TextField::new('description', 'Description'),
            
            // Producteurices : voir les noms des prod dans INDEX/ vue dashboard
            TextField::new('nomProducteurices', 'Producteurices')
                ->onlyOnIndex(), 

            // Producteurices : voir les cases à cocher dans edit
            AssociationField::new('producteurices', 'Producteurices') // cases à cocher
                ->setFormTypeOption('expanded', true)
                ->setFormTypeOption('multiple', true)
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),


            // Affichage des médias liés au produit (en index)
            TextField::new('nomMedias', 'Médias')
                ->formatValue(function ($value, Produit $produit) 
                {
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

            // OneToMany Medias : édition des médias existants via MediaCrudController
            CollectionField::new('medias', 'Photos / Fichiers')
                ->useEntryCrudForm(MediaCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),

            // Upload multiple : champ NON mappé
            Field::new('photos')
                ->setFormType(FileType::class)
                ->setFormTypeOptions([
                    'multiple' => true,
                    'required' => false,
                ])
                ->setLabel('Photos supplémentaires')
                ->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Produit) {
            $this->handleUploads($entityInstance);
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Produit) {
            $this->handleUploads($entityInstance);
        }

        parent::updateEntity($em, $entityInstance);
    }


    private function handleUploads(Produit $produit): void
    {
        foreach ($produit->getPhotos() as $uploadedFile) {
            if ($uploadedFile === null) {
                continue;
            }

            $media = new Media();
            $media->setFile($uploadedFile); // Vich va gérer l’upload
            $media->setProduit($produit); // ManyToOne vers Produit
            $media->setRole('photo_supplementaire');

            $produit->addMedia($media); // cascade persist = OK
        }

        $produit->setPhotos([]); // on vide le “sac”
    }
}
