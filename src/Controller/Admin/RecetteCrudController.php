<?php
namespace App\Controller\Admin;

use App\Entity\Media;
use App\Entity\Recette;
use App\Controller\Admin\MediaCrudController;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
// use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class RecetteCrudController extends AbstractCrudController
{
    private UploaderHelper $uploaderHelper; 
    public function __construct(UploaderHelper $uploaderHelper) 
    { 
        $this->uploaderHelper = $uploaderHelper; 
    }


    public static function getEntityFqcn(): string
    {
        return Recette::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('titre', 'Titre'),

            AssociationField::new('auteurice', 'Auteurice')->hideOnForm(),

            IntegerField::new('nombreMangeurs', 'Nombre de mangeurs'),

            TextareaField::new('ingredients', 'Ingrédients'),

            TextareaField::new('description', 'Description'),

            // produit : champ liste dans l’index 
            AssociationField::new('produit', 'Produits utilisés')
                ->formatValue(function ($value, $entity) {
                    return implode(
                        ', ',
                        $entity->getProduit()
                            ->map(fn($p) => $p->__toString())
                            ->toArray()
                    );
                })
                ->onlyOnIndex(),

            // produit : champ liste deroulante dans le formulaire
            AssociationField::new('produit', 'Produits utilisés')
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),

            // champ miniature photo 
            TextField::new('titre', 'Photo')
                ->onlyOnIndex()
                ->formatValue(function ($value, $recette) {
                    $media = $recette->getMedias()
                        ->filter(fn($m) => $m->getRole() === 'photo_principale')
                        ->first();
                
                    if (!$media) {
                        return '';
                    }
                
                    $url = $this->uploaderHelper->asset($media, 'file');
                
                    return sprintf('<img src="%s" style="height:3rem;width: 3.7rem;border-radius:4px;">', $url);
                })
                ->renderAsHtml(),
            

// ------------------ajout modif/change photo MAIS RIEN NE BOUGE------------------
            // TextField::new('titre', 'Photo actuelle')
            //     ->onlyOnForms()
            //     ->formatValue(function ($value, $recette) {
            //         $media = $recette->getMedias()
            //             ->filter(fn($m) => $m->getRole() === 'photo_principale')
            //             ->first();

            //         if (!$media) {
            //             return '';
            //         }

            //         $filename = $media->getNomFichier();

            //         return sprintf(
            //             '<div style="display:flex;align-items:center;gap:1rem;">
            //                 <span>%s</span>
            //                 <button type="button"
            //                     onclick="document.getElementById(\'changer-photo\').click()"
            //                     style="padding:0.3rem 0.6rem;border:1px solid #ccc;border-radius:4px;background:#f9f9f9;">
            //                     Changer la photo
            //                 </button>
            //             </div>',
            //             $filename
            //     );
            // })
            // ->renderAsHtml(),
// ------------------
            Field::new('newPhoto', '')
                ->setFormType(FileType::class)
                ->setFormTypeOptions([
                    'mapped' => false,
                    'required' => false,
                    'attr' => ['id' => 'changer-photo', 'style' => 'display:none;'],
                ])
                ->onlyOnForms(),

// ------------------ soit celui ci ------------------
            // CollectionField::new('medias', 'Photos / Fichiers')
            //     ->onlyOnForms()
            //     ->setTemplatePath('admin/recette/_media_readonly.html.twig'),
// ------------------ soit celui ci ------------------
            CollectionField::new('medias', 'Photos / Fichiers')
                ->useEntryCrudForm(MediaCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),
        ];
    }
///////// OK

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void 
    { 
        $uploadedFile = $this->getContext()->getRequest()->files->get('Recette')['newPhoto'] ?? null; 
        
        if ($uploadedFile) { 
            // Supprimer l’ancienne photo principale 
            foreach ($entityInstance->getMedias() as $media) { 
                if ($media->getRole() === 'photo_principale') { 
                    $em->remove($media); 
                } 
            } 
            // Créer le nouveau Media 
            $media = new Media(); 
            $media->setFile($uploadedFile); 
            $media->setRole('photo_principale'); 
            $media->setPage('recette'); 
            $media->setRecette($entityInstance); 

            $em->persist($media); 
        } 
        parent::updateEntity($em, $entityInstance); 
    }
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Recette) {
            // date automatique comme l'ID
            if (!$entityInstance->getDatePublication()) {
                $entityInstance->setDatePublication(new \DateTimeImmutable());
            }
        }

        parent::persistEntity($em, $entityInstance);
    }
}
