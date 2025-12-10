<?php

namespace App\Controller\Admin;

use App\Entity\Ressource;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;    
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;


class RessourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ressource::class;
    }

    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id')->hideOnForm(),
    //         ChoiceField::new('statut')
    //             ->setLabel('Statut')
    //             ->setChoices([
    //                 'Non validée' => 'non_validée',
    //                 'Publiée'     => 'publiée',
    //                 'Archivée'    => 'archivée',
    //             ]),
    //         DateTimeField::new('datePublication')
    //             ->setLabel('Date de publication')
    //             ->setFormTypeOption('disabled', true),                
    //         TextField::new('titre'),
    //         TextField::new('sousTitre'),
    //         TextareaField::new('ressourceTexte'),
    //         AssociationField::new('categorie'),
    //         AssociationField::new('user'),
    //         AssociationField::new('pole'),
    //         AssociationField::new('photoPrincipale'),
    //         AssociationField::new('photos'),
    //     ];
    // }

    // meme chose avec yield MAIS + ECORESPONSABLE
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
    
        yield ChoiceField::new('statut')
            ->setLabel('Statut')
            ->setChoices([
                'Non validée' => 'non_validée',
                'Publiée'     => 'publiée',
                'Archivée'    => 'archivée',
            ]);
        
        yield DateTimeField::new('datePublication')
            ->setLabel('Date de publication')
            ->setFormTypeOption('disabled', true);
        
        yield TextField::new('titre')->setLabel('Titre');
        yield TextField::new('sousTitre')->setLabel('Sous-titre');
        yield TextareaField::new('ressourceTexte')->setLabel('Texte');
        
        yield AssociationField::new('categorie')->setLabel('Catégorie');
        yield AssociationField::new('user')->setLabel('Auteur');
        yield AssociationField::new('pole')->setLabel('Pôle');
        
        yield AssociationField::new('photoPrincipale')->setLabel('Photo principale');
        yield AssociationField::new('photos')->setLabel('Photos supplémentaires');
    }
}
