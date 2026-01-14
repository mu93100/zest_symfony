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
        TextEditorField::new('description', 'Description')->hideOnIndex(),

        // --- INDEX : on affiche les images si Media existe ---
        ImageField::new('logoMediaPath', 'Logo')
            ->setBasePath('uploads/medias')
            ->onlyOnIndex(),

        ImageField::new('photoPrincipalePath', 'Photo principale')
            ->setBasePath('uploads/medias')
            ->onlyOnIndex(),

        TextField::new('nomProduits', 'Produits')->onlyOnIndex(),

        // --- FORMULAIRE : UNIQUEMENT les champs d’upload (pas de preview) ---
        Field::new('logo')
            ->setFormType(FileType::class)
            ->setLabel('Nouveau logo')
            ->setFormTypeOptions([
                'required' => false,
                'data_class' => null,
            ])
            ->onlyOnForms(),

        Field::new('photoPrincipale')
            ->setFormType(FileType::class)
            ->setLabel('Nouvelle photo principale')
            ->setFormTypeOptions([
                'required' => false,
                'data_class' => null,
            ])
            ->onlyOnForms(),

        Field::new('photosSupplementaires')
            ->setFormType(FileType::class)
            ->setLabel('Nouvelles photos supplémentaires')
            ->setFormTypeOptions([
                'multiple' => true,
                'required' => false,
                'data_class' => null,
            ])
            ->onlyOnForms(),

        AssociationField::new('produits', 'Produits')
            ->setFormTypeOptions([
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false,
            ])
            ->onlyOnForms(),

        TextField::new('slug')->onlyOnIndex(),
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

    private function handleUploads(Producteurice $p): void
    {
        // dump('UPLOADS', $p->getLogo(), $p->getPhotoPrincipale(), $p->getPhotosSupplementaires()); die; 

        if ($p->getLogo()) {
            $media = new Media();
            $media->setFile($p->getLogo());
            $media->setProducteurice($p);
            $media->setRole('logo');
            $p->addMedia($media);
        }

        if ($p->getPhotoPrincipale()) {
            $media = new Media();
            $media->setFile($p->getPhotoPrincipale());
            $media->setProducteurice($p);
            $media->setRole('photo_principale');
            $p->addMedia($media);
        }

        foreach ($p->getPhotosSupplementaires() as $file) {
            if ($file) {
                $media = new Media();
                $media->setFile($file);
                $media->setProducteurice($p);
                $media->setRole('photo_supplementaire');
                $p->addMedia($media);
            }
        }

        $p->setLogo(null);
        $p->setPhotoPrincipale(null);
        $p->setPhotosSupplementaires([]);
    }
}
