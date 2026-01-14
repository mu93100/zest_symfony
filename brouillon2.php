<?php

namespace App\Controller\Admin;

use App\Entity\Producteurice;
use App\Entity\Media;
use App\Controller\Admin\MediaCrudController;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField; // a commenter
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField; // a commenter
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\Common\Collections\ArrayCollection; // pour miniature photos supplementaires en index
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField; // pour description (longText)

use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
// avec VICH dans entity media utilisé sur propriété file 
// #[Vich\UploadableField(mapping: 'medias', fileNameProperty: 'nomFichier')]
//     private ?File $file = null;


class ProducteuriceCrudController extends AbstractCrudController
{
    private UploaderHelper $uploaderHelper; // --------pour uploader les photos avec VICH
    public function __construct(UploaderHelper $uploaderHelper) 
    { 
        $this->uploaderHelper = $uploaderHelper; 
    }


    public static function getEntityFqcn(): string
    {
        return Producteurice::class;
    }                                       //-------------------------------------------




    // private EntityManagerInterface $em;

    // public function __construct(EntityManagerInterface $em)
    // {
    //     $this->em = $em;
    // }

    // public static function getEntityFqcn(): string
    // {
    //     return Producteurice::class;
    // }

    public function configureFields(string $pageName): iterable // affichage des champs dans admin
    
    {
        return [

            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom'),
            TextField::new('slug')->onlyOnForms(),
            BooleanField::new('isCoop', 'Coopérative ?')->onlyOnIndex(),
            TextField::new('site', 'Site web'),
            // TextField::new('lienProduits', 'Lien externe vers produits'),

            // produits : voir les noms des produits dans INDEX
            TextField::new('nomProduits', 'Produits')->onlyOnIndex(),

            // produits : voir les noms des produits dans FORM
            AssociationField::new('producteurices', 'Producteurices') 
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),
            // AssociationField::new('produits', 'Produits')
            // ->setFormTypeOptions([
            //     'expanded' => true,
            //     'multiple' => true,
            //     'by_reference' => false,
            //     'attr' => [
            // 'class' => 'd-flex flex-wrap gap-2 p-2'  // Bootstrap flex
            //     ]
            // ])
            // // ->setTemplatePath('admin/fields/produits_flex_row.html.twig') // pour CSS personnalisé
            // ->onlyOnForms(),

            TextEditorField::new('description', 'Description'),

            // champ miniature photo_principale en INDEX
            TextField::new('nom', 'logo')
                ->formatValue(function ($value, $produit) {
                    $media = $produit->getMedias()
                        ->filter(fn($m) => $m->getRole() === 'logo')
                        ->first();
                
                    if (!$media) {return '';}

                    $url = $this->uploaderHelper->asset($media, 'file');

                    return sprintf('<img src="%s" style="height:3rem;width: 3.7rem;">', $url);
                })
                ->renderAsHtml()
                ->onlyOnIndex(),

            TextField::new('nom', 'Photo principale')
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




//             // images : affichage I N D E X  only
//             ImageField::new('logoMediaPath', 'Logo')
//                 ->setBasePath('/uploads/medias')
//                 ->onlyOnIndex(),

//             ImageField::new('photoPrincipalePath', 'Photo principale')
//                 ->setBasePath('/uploads/medias')
//                 ->onlyOnIndex(),

//             Field::new('photosSupplementairesPaths', 'Photos ++')
//                 // ->setTemplatePath('admin/fields/photos_supplementaires.html.twig')
//                 ->onlyOnIndex(),


//             // images : affichage F O R M U L A I R E  only

//             // Field::new('photosSupplementairesPaths', 'Photos supplémentaires actuelles')
//             //     ->setTemplatePath('admin/fields/photos_supplementaires.html.twig')
//             //     ->onlyOnForms(),

//             // TextField::new('logoMediaPath', 'Logo actuel')
//             //     ->onlyOnForms()
//             //     ->setFormTypeOption('disabled', true),

//             // Field::new('removeLogo', 'Supprimer le logo')
//             //     ->setFormTypeOption('mapped', false)
//             //     ->setFormTypeOption('required', false)
//             //     ->setFormType(\Symfony\Component\Form\Extension\Core\Type\CheckboxType::class)
//             //     ->onlyOnForms(),
// //  ON TOUCHE PAS
//             TextField::new('logoMediaPath', 'Logo actuel')
//                 ->onlyOnForms()
//                 ->setFormTypeOption('disabled', true),


//             Field::new('logo', 'Nouveau logo')
//                 ->setFormType(FileType::class)
//                 ->setFormTypeOptions(['required' => false, 'data_class' => null])
//                 ->onlyOnForms(),

//             Field::new('photoPrincipale', 'Nouvelle photo principale')
//                 ->setFormType(FileType::class)
//                 ->setFormTypeOptions(['required' => false, 'data_class' => null])
//                 ->onlyOnForms(),

//             Field::new('photosSupplementaires', 'Nouvelles photos supplémentaires')
//                 ->setFormType(FileType::class)
//                 ->setFormTypeOptions(['multiple' => true, 'required' => false, 'data_class' => null])
//                 ->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $this->handleUploads($entityInstance);
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $this->handleUploads($entityInstance);
        }

        parent::updateEntity($em, $entityInstance);
    }

    private function handleUploads(Producteurice $producteurice): void // function handleUploads = gestion des uploads
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
//         // LOGO
//         if ($p->getLogo()) {
//             $media = new Media();
//             $media->setRole('logo');            // 1️⃣ D’ABORD LE ROLE
//             $media->setFile($p->getLogo());     // 2️⃣ ENSUITE LE FICHIER
//             $p->addMedia($media);
//             $this->em->persist($media);
//         }

//         $request = $this->getContext()->getRequest(); 
//         $removeLogo = $request->get('Producteurice')['removeLogo'] ?? false; if ($removeLogo) { $oldLogo = $p->getMedias()->filter(fn($m) => $m->getRole() === 'logo')->first(); if ($oldLogo) { $p->removeMedia($oldLogo); $this->em->remove($oldLogo); } } 
//         // PHOTO PRINCIPALE
//         if ($p->getPhotoPrincipale()) {
//             $media = new Media();
//             $media->setRole('photo_principale');    // 1️⃣
//             $media->setFile($p->getPhotoPrincipale()); // 2️⃣
//             $p->addMedia($media);
//             $this->em->persist($media);
//         }

//         // PHOTOS SUPPLÉMENTAIRES
//         foreach ($p->getPhotosSupplementaires() as $file) {
//             if ($file) {
//                 $media = new Media();
//             $media->setRole('photo_supplementaire'); // 1️⃣
//             $media->setFile($file);                  // 2️⃣
//             $p->addMedia($media);
//             $this->em->persist($media);
//             }
//         }

//         // Reset des champs virtuels
//         $p->setLogo(null);
//         $p->setPhotoPrincipale(null);
//         $p->setPhotosSupplementaires([]);
//         $this->em->flush();
    }
}
