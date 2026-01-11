<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field; 
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('nomFichier', 'Nom du fichier')
                ->onlyOnIndex(),

            TextField::new('description', 'Description'),

            Field::new('file')
                ->setFormType(VichFileType::class) 
                ->setLabel('Photo principale') 
                ->onlyOnForms(),

            Field::new('files')
                ->setFormType(FileType::class)
                ->setFormTypeOptions([ 
                    'multiple' => true, 
                    'mapped' => false, 
                    'required' => false, 
                ]) 
                ->setLabel('Photos supplémentaires') 
                ->onlyOnForms(),

            ChoiceField::new('page')
                ->setChoices([
                    'Recette' => 'recette',
                    'Produit' => 'produit',
                    'Producteurice' => 'producteurice',
                    'Ressource' => 'ressource',
                ]),
            
            
            ChoiceField::new('role')
                ->setChoices([
                    'Photo principale' => 'photo_principale',
                    'Photo supplémentaire' => 'photo_supplementaire',
                    'Fichier' => 'fichier',
                    'Vidéo' => 'video',
                    'Logo' => 'logo',
                ]),


            // AssociationField::new('recette'),
            // AssociationField::new('produit'),
            // AssociationField::new('producteurice'),
            // AssociationField::new('ressource'),
        ];
    }
}
