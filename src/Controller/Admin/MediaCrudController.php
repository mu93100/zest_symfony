<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field; 
use Vich\UploaderBundle\Form\Type\VichFileType;


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

            Field::new('file') // champ VichUploader pour uploader les fichiers
                ->setFormType(VichFileType::class) 
                ->setLabel('Photo principale') 
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

            // affichage des titres et noms des instances (avec le -toString dans entité)     
            AssociationField::new('recette'),
            AssociationField::new('produit'),
            AssociationField::new('producteurice'),
            AssociationField::new('ressource'),
        ];
    }
}
