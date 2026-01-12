<?php

namespace App\Controller\Admin;

use App\Entity\Producteurice;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProducteuriceCrudController extends AbstractCrudController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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

            ImageField::new('logoMediaPath', 'Logo')
                ->setBasePath('/uploads/medias')
                ->onlyOnIndex(),

            ImageField::new('photoPrincipalePath', 'Photo principale')
                ->setBasePath('/uploads/medias')
                ->onlyOnIndex(),

            Field::new('photosSupplementairesPaths', 'Photos ++')
                ->setTemplatePath('admin/fields/photos_supplementaires.html.twig')
                ->onlyOnIndex(),

            Field::new('logo', 'Nouveau logo')
                ->setFormType(FileType::class)
                ->setFormTypeOptions(['required' => false, 'data_class' => null])
                ->onlyOnForms(),

            Field::new('photoPrincipale', 'Nouvelle photo principale')
                ->setFormType(FileType::class)
                ->setFormTypeOptions(['required' => false, 'data_class' => null])
                ->onlyOnForms(),

            Field::new('photosSupplementaires', 'Nouvelles photos supplémentaires')
                ->setFormType(FileType::class)
                ->setFormTypeOptions(['multiple' => true, 'required' => false, 'data_class' => null])
                ->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        $this->handleUploads($entityInstance);
        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        $this->handleUploads($entityInstance);
        parent::updateEntity($em, $entityInstance);
    }

    private function handleUploads(Producteurice $p): void
    {
        // LOGO
        if ($p->getLogo()) {
            $media = new Media();
            $media->setFile($p->getLogo());
            $media->setRole('logo');
            $media->setProducteurice($p);
            $p->addMedia($media);
            $this->em->persist($media);
        }

        // PHOTO PRINCIPALE
        if ($p->getPhotoPrincipale()) {
            $media = new Media();
            $media->setFile($p->getPhotoPrincipale());
            $media->setRole('photo_principale');
            $media->setProducteurice($p);
            $p->addMedia($media);
            $this->em->persist($media);
        }

        // PHOTOS SUPPLÉMENTAIRES
        foreach ($p->getPhotosSupplementaires() as $file) {
            if ($file) {
                $media = new Media();
                $media->setFile($file);
                $media->setRole('photo_supplementaire');
                $media->setProducteurice($p);
                $p->addMedia($media);
                $this->em->persist($media);
            }
        }

        // Reset des champs virtuels
        $p->setLogo(null);
        $p->setPhotoPrincipale(null);
        $p->setPhotosSupplementaires([]);
        $this->em->flush();
    }
}
