<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\Media;
use App\Controller\Admin\MediaCrudController;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\Common\Collections\ArrayCollection; // pour miniature photos supplementaires en index
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField; // pour description (longText)

use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
// avec VICH dans entity media utilisé sur propriété file 
// #[Vich\UploadableField(mapping: 'medias', fileNameProperty: 'nomFichier')]
//     private ?File $file = null;


class ProduitCrudController extends AbstractCrudController
{
    private UploaderHelper $uploaderHelper; // --------pour uploader les photos avec VICH

    public function __construct(UploaderHelper $uploaderHelper) 
    { 
        $this->uploaderHelper = $uploaderHelper; 
    }
    
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }                                       //--------------------------------------------

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('nom', 'Nom'),

            TextEditorField::new('description', 'Description'),

            // producteurices : voir les noms des prod dans INDEX
            TextField::new('nomProducteurices', 'Producteurices')
                ->onlyOnIndex(), 
            
            // producteurices : champ liste deroulante dans FORM
            AssociationField::new('producteurices', 'Producteurices') 
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),

            // champ miniature photo_principale en INDEX
            TextField::new('nom', 'Photo')
                ->formatValue(function ($value, $produit) {
                    $media = $produit->getMedias()
                        ->filter(fn($m) => $m->getRole() === 'photo_principale')
                        ->first();
                
                    if (!$media) {return '';}

                    $url = $this->uploaderHelper->asset($media, 'file');

                    return sprintf('<img src="%s" style="height:3rem;width: 3.7rem;border-radius:4px;">', $url);
                })
                ->renderAsHtml()
                ->onlyOnIndex(),

            // affichage des photos supplementaires en INDEX
            TextField::new('nom', 'Photos supplementaires')
                ->formatValue(function ($value, $produit) {
                    $medias = $produit->getMedias() 
                        ->filter(fn($m) => $m->getRole() === 'photo_supplementaire') 
                        ->slice(0, 7); // slice() renvoie un array
                    
                    if (empty($medias)) return '';

                    $images = array_map(function($media) {
                            $url = $this->uploaderHelper->asset($media, 'file');
                            return sprintf('<img src="%s" style="height:3rem;width: 3.7rem;border-radius:4px;">', $url);
                        }, $medias);

                        return implode('', $images);
                    })
                    ->renderAsHtml()
                    ->onlyOnIndex(),
// ------------------
            // affichage des médias existants pour modif dans FORM
            CollectionField::new('medias', 'Photos / Fichiers')
                ->useEntryCrudForm(MediaCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->setLabel('Modifier les photos')
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
