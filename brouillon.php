<?php

namespace App\Controller\Admin;

use App\Entity\Pole;
use App\Entity\User;
use App\Entity\Media;
use App\Controller\Admin\MediaCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class PoleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string 
    { 
        return Pole::class; 
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('nom', 'Nom'),

            TextEditorField::new('descriptif', 'Descriptif')
                ->setTemplatePath('admin/fields/text_editor.html.twig'),

            // ------------ champ descriptif_pdf INDEX ------------
            TextField::new('descriptif_pdf', 'Fichiers')
                ->formatValue(function ($value, Pole $pole) {

                    $medias = $pole->getMedias()
                        ->filter(fn($m) => $m->getRole() === 'fichier_supplementaire')
                        ->slice(0, 7);

                    if ($medias->isEmpty()) {
                        return '';
                    }

                    // affichage nom des fichiers
                    $names = array_map(
                        fn(Media $media) => $media->getNomFichier(),
                        $medias->toArray()
                    );

                    return implode(', ', $names);
                })
                ->onlyOnIndex(),

            // ------------ champ descriptif_pdf FORM ------------
            CollectionField::new('medias', 'Fichiers')
                ->useEntryCrudForm(MediaCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),

            // ------------ propositions des adhérents INDEX -------------
            ArrayField::new('propositions', 'Propositions des adhérents')
                ->onlyOnIndex()
                ->formatValue(function ($value, Pole $pole) {

                    $propositions = [];

                    foreach ($pole->getAdhesions() as $adhesion) {
                        $user = $adhesion->getUser();
                        if ($user) {
                            $propositions[] = sprintf(
                                '%s %s (%s)',
                                $user->getPrenom(),
                                $user->getNom(),
                                $user->getEmail()
                            );
                        }
                    }

                    return empty($propositions)
                        ? '<i>Aucune proposition</i>'
                        : implode('<br>', $propositions);
                })
                ->renderAsHtml(),

            //---------------- membres officiels du pôle ----------------
            ArrayField::new('users', 'Membres officiels')
                ->onlyOnIndex()
                ->formatValue(function ($value, Pole $pole) {

                    $members = [];

                    foreach ($pole->getUsers() as $user) {
                        $members[] = sprintf(
                            '%s %s (%s)',
                            $user->getPrenom(),
                            $user->getNom(),
                            $user->getEmail()
                        );
                    }

                    return empty($members)
                        ? '<i>Aucun membre</i>'
                        : implode('<br>', $members);
                })
                ->renderAsHtml(),

            //---------------- FORM : gestion des membres officiels ----------------
            AssociationField::new('users', 'Membres officiels')
                ->onlyOnForms()
                ->setFormTypeOptions([
                    'multiple' => true,
                    'by_reference' => false,
                    'choice_label' => fn(User $user) =>
                        sprintf('%s %s (%s)', $user->getPrenom(), $user->getNom(), $user->getEmail()),
                ]),
        ];
    }
}
