<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

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

            TextField::new('nomFichier', 'Nom du fichier'),

            TextField::new('description', 'Description'),

            ChoiceField::new('type')
                ->setChoices([
                    'Image' => 'image',
                    'PDF' => 'pdf',
                    'Document' => 'doc',
                    'Vidéo' => 'mp4',
                ]),

            ChoiceField::new('role')
                ->setChoices([
                    'Photo principale' => 'photo_principale',
                    'Photo supplémentaire' => 'photo_supplementaire',
                    'Fichier' => 'fichier',
                    'Vidéo' => 'video',
                    'Logo' => 'logo',
                ]),

            AssociationField::new('recette'),
            AssociationField::new('produit'),
            AssociationField::new('producteurice'),
            AssociationField::new('ressource'),
        ];
    }
}
