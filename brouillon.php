<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Entity\Producteurice;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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

            TextField::new('nom', 'Nom du producteurice'),

            TextEditorField::new('description', 'Description')
                ->hideOnIndex(), // description visible uniquement en édition

            TextField::new('logoMedia', 'Logo')
                ->formatValue(function ($value, Producteurice $p) {
                    $logo = $p->getMedias()->filter(
                        fn (Media $m) => 'logo' === $m->getRole()
                    )->first();

                    return $logo ? $logo->getNomFichier() : '—';
                })
        ->onlyOnIndex(),

            TextField::new('photoPrincipaleMedia', 'Photo principale')
                ->formatValue(function ($value, Producteurice $p) {
                    $photo = $p->getMedias()->filter(
                        fn (Media $m) => 'photo_principale' === $m->getRole()
                    )->first();

                    return $photo ? $photo->getNomFichier() : '—';
                })
                ->onlyOnIndex(),

            TextField::new('photosSuppMedia', 'Photos supplémentaires')
                    ->formatValue(function ($value, Producteurice $p) {
                        $photos = $p->getMedias()->filter(
                            fn (Media $m) => 'photo_supplementaire' === $m->getRole()
                        );

                        if ($photos->isEmpty()) {
                            return '—';
                        }

                        return implode(', ', $photos
                            ->map(fn (Media $m) => $m->getNomFichier())
                            ->toArray());
                    })
                ->onlyOnIndex(),

            // RELATION ManyToMany PRODUITS
            AssociationField::new('produits', 'Produits')
                ->setFormTypeOptions(['by_reference' => false]),

            TextField::new('slug')
                ->onlyOnIndex(), // visible uniquement dans la liste admin
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $entityInstance->generateSlug(); // génération automatique du slug
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $entityInstance->generateSlug(); // mise à jour du slug
        }

        parent::updateEntity($em, $entityInstance);
    }
}
