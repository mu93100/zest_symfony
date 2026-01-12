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
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

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

    public function configureFields(string $pageName): iterable // affichage des champs dans admin
    {
        return [

            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom'),
            TextField::new('slug')->onlyOnForms(),
            BooleanField::new('isCoop', 'Coopérative ?'),
            TextField::new('site', 'Site web'),
            // TextField::new('lienProduits', 'Lien externe vers produits'),

            TextField::new('nomProduits', 'Produits')->onlyOnIndex(),

            AssociationField::new('produits', 'Produits')
            ->setFormTypeOptions([
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false,
            ])
            ->onlyOnForms(),

            TextEditorField::new('description', 'Description'),

            // images : affichage I N D E X  only
            ImageField::new('logoMediaPath', 'Logo')
                ->setBasePath('/uploads/medias')
                ->onlyOnIndex(),

            ImageField::new('photoPrincipalePath', 'Photo principale')
                ->setBasePath('/uploads/medias')
                ->onlyOnIndex(),

            Field::new('photosSupplementairesPaths', 'Photos ++')
                ->setTemplatePath('admin/fields/photos_supplementaires.html.twig')
                ->onlyOnIndex(),


            // images : affichage F O R M U L A I R E  only
            TextField::new('logoMediaPath', 'Logo actuel')
                ->onlyOnForms()
                ->setFormTypeOption('disabled', true),

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

    private function handleUploads(Producteurice $p): void // function handleUploads = gestion des uploads
    {
        // LOGO
        if ($p->getLogo()) {
            $media = new Media();
            $media->setRole('logo');            // 1️⃣ D’ABORD LE ROLE
            $media->setFile($p->getLogo());     // 2️⃣ ENSUITE LE FICHIER
            $p->addMedia($media);
            $this->em->persist($media);
        }

        // PHOTO PRINCIPALE
        if ($p->getPhotoPrincipale()) {
            $media = new Media();
            $media->setRole('photo_principale');    // 1️⃣
            $media->setFile($p->getPhotoPrincipale()); // 2️⃣
            $p->addMedia($media);
            $this->em->persist($media);
        }

        // PHOTOS SUPPLÉMENTAIRES
        foreach ($p->getPhotosSupplementaires() as $file) {
            if ($file) {
                $media = new Media();
            $media->setRole('photo_supplementaire'); // 1️⃣
            $media->setFile($file);                  // 2️⃣
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
