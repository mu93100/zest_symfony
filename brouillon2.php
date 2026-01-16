<?php

namespace App\Controller\Admin;

use App\Entity\Adhesion;
use App\Entity\Saison;
use App\Entity\User;
use App\Entity\Groupe;
use App\Entity\MontantAdhesion;
use App\Entity\Motivation;
use App\Entity\Dispo;
use App\Entity\Pole;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\HttpFoundation\Response;  //à supprimer
// use EasyCorp\Bundle\EasyAdminBundle\Config\Templates;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;  //à supprimer
use Symfony\Component\HttpFoundation\Request; //à supprimer
use Doctrine\ORM\EntityManagerInterface; //à supprimer NON
use EasyCorp\Bundle\EasyAdminBundle\Config\Templates;
use App\Repository\SaisonRepository;
use App\Repository\AdhesionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;



class AdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adhesion::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            
            DateTimeField::new('dateAdhesion', 'Date d\'adhésion'),
            AssociationField::new('user', 'Adhérent'),
            AssociationField::new('groupe', 'Groupe'),
            AssociationField::new('saison')
                ->setLabel('Saison')
                ->setRequired(true),
            
            
            BooleanField::new('paiementValide', 'Paiement effectué')->hideOnForm(),
            // integerField::new('paiement', 'Montant libre'),
            TextField::new('montantPaiementLibre', 'Montant libre')
                ->formatValue(function ($value) {
                    return $value ? $value . '€' : '—';
                })
                ->onlyOnIndex(),

                AssociationField::new('montantAdhesion', 'Montant pré-défini')
    ->setFormTypeOptions(['by_reference' => false, 'required' => false])
    ->onlyOnForms(),
    
            // ✅ + Montant libre (input numérique)
            IntegerField::new('montantPaiementLibre', 'OU Montant libre (€)')
                ->setFormTypeOptions([
                    'attr' => ['min' => 0],
                    'empty_data' => null,
                    'required' => false
                ])
                ->onlyOnForms(),
        ];
    }
}



************************* commit recette/produit producteurices OKeasyadmin
avec modif entity recette : 
    #[ORM\Column(length: 500)]
    #[ORM\Column(type: Types::TEXT)] // ajout
    private ?string $ingredients = null;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
// avec VICH dans entity media utilisé sur propriété file 
// #[Vich\UploadableField(mapping: 'medias', fileNameProperty: 'nomFichier')]
//     private ?File $file = null;


class RecetteCrudController extends AbstractCrudController
{
    private UploaderHelper $uploaderHelper; // --------pour uploader les photos avec VICH
    public function __construct(UploaderHelper $uploaderHelper) 
    { 
        $this->uploaderHelper = $uploaderHelper; 
    }


    public static function getEntityFqcn(): string
    {
        return Recette::class;
    }                                       //----------------


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('titre', 'Titre'),

            AssociationField::new('auteurice', 'Auteurice'),

            IntegerField::new('nombreMangeurs', 'Nombre de mangeurs'),

            TextEditorField::new('ingredients', 'Ingrédients'),

            TextEditorField::new('description', 'Description'),

            //  champ liste produits dans l’index 
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

            // champ liste deroulante produits dans le formulaire
            AssociationField::new('produit', 'Produits utilisés')
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),

            // champ miniature photo en index
            TextField::new('titre', 'Photo')
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
                ->renderAsHtml()
                ->onlyOnIndex(),
            

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
                    // 'attr' => ['id' => 'changer-photo', 'style' => 'display:none;'],
                ])
                ->setLabel('Modifier la photo principale')
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