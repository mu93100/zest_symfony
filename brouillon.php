<!-- ANCIEN ProducteuriceCrudController -->
<?php 

namespace App\Controller\Admin;

use App\Entity\Producteurice;
use App\Entity\Produit;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\FileType;use EasyCorp\Bundle\EasyAdminBundle\Field\Field; 
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;


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

            TextField::new('nom', 'Nom'),

            BooleanField::new('isCoop', 'Coopérative ?'),

            TextField::new('site', 'Site web'),

            TextField::new('lienProduits', 'Lien vers les produits'),

            TextEditorField::new('description', 'Description')
                ->hideOnIndex(), // description visible uniquement en édition


            // miniatures photos/logos
            ImageField::new('logoMediaPath', 'Logo')
                ->setBasePath('uploads/medias')
                ->onlyOnIndex(),

            ImageField::new('photoPrincipalePath', 'Photo principale')
                ->setBasePath('uploads/medias')
                ->onlyOnIndex(),

            //pour field plusieurs miniatures avec création de 
            // templates/admin/fields/photos_supplementaires.html.twig
            Field::new('photosSupplementairesPaths', 'Photos')
                ->setCustomOption('mapped', false)
                ->setTemplatePath('admin/fields/photos_supplementaires.html.twig')
                ->onlyOnIndex(),


            TextField::new('nomMedias', 'Médias')
                ->onlyOnIndex(),

            // Field::new('photos')
            //     ->setFormType(FileType::class)
            //     ->setFormTypeOptions([
            //         'multiple' => true,
            //         'required' => false,
            //     ])
                // ->setLabel('Photos'),
            // miniatures photos/logos
            Field::new('logo')
                ->setFormType(FileType::class)
                ->setLabel('Logo')
                ->setFormTypeOptions(['required' => false])
                ->onlyOnForms(),

            Field::new('photoPrincipale')
                ->setFormType(FileType::class)
                ->setLabel('Photo principale')
                ->setFormTypeOptions(['required' => false])
                ->onlyOnForms(),

            Field::new('photosSupplementaires')
                ->setFormType(FileType::class)
                ->setLabel('Photos supplémentaires')
                ->setFormTypeOptions([
                'multiple' => true,
                'required' => false,
            ])
            ->onlyOnForms(),
            
                
            // produit : voir les noms dans INDEX/ vue dashboard
            TextField::new('nomProduits', 'Produits')
                ->onlyOnIndex(), 

            // Produit : voir les cases à cocher dans edit
            AssociationField::new('produits', 'Produits') // cases à cocher
                ->setFormTypeOptions([ 
                    'expanded' => true, 
                    'multiple' => true, 
                    'by_reference' => false, 
                ])
                ->onlyOnForms(),

            TextField::new('slug')
                ->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $this->handleUploads($entityInstance);
            $entityInstance->generateSlug();
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Producteurice) {
            $this->handleUploads($entityInstance);
            $entityInstance->generateSlug();
        }

        parent::updateEntity($em, $entityInstance);
    }

    // private function handleUploads(Producteurice $producteurice): void
    // {
    //     foreach ($producteurice->getPhotos() as $uploadedFile) {
    //         if ($uploadedFile === null) {
    //             continue;
    //         }

    //         $media = new Media();
    //         $media->setFile($uploadedFile);
    //         $media->setProducteurice($producteurice);
    //         $media->setRole('photo_supplementaire');

    //         $producteurice->addMedia($media);
    //     }

    //     $producteurice->setPhotos([]);
    // }
    private function handleUploads(Producteurice $p): void
    {
        // LOGO
        if ($p->getLogo()) {
            $media = new Media();
            $media->setFile($p->getLogo());
            $media->setProducteurice($p);
            $media->setRole('logo');
            $p->addMedia($media);
        }

        // PHOTO PRINCIPALE
        if ($p->getPhotoPrincipale()) {
            $media = new Media();
            $media->setFile($p->getPhotoPrincipale());
            $media->setProducteurice($p);
            $media->setRole('photo_principale');
            $p->addMedia($media);
        }

        // PHOTOS SUPPLÉMENTAIRES
        foreach ($p->getPhotosSupplementaires() as $file) {
            if ($file) {
                $media = new Media();
                $media->setFile($file);
                $media->setProducteurice($p);
                $media->setRole('photo_supplementaire');
                $p->addMedia($media);
            }
        }

        // Nettoyage des champs non mappés
        $p->setLogo(null);
        $p->setPhotoPrincipale(null);
        $p->setPhotosSupplementaires([]);
    }

}



// 
<?php

namespace App\Entity;

use App\Entity\Produit;
use App\Entity\Producteurice;
use App\Entity\Ressource;
use App\Entity\Recette;
use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;




#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[Vich\Uploadable]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // upload simple
    // activer le bundle VichUploader pour uploader TOUT format de fichier
    // terminal : composer require vich/uploader-bundle / pas de colonne file en BDD
    // et création de src/EventListener/MediaMultipleUploadSubscriber.php
    #[Vich\UploadableField(mapping: 'medias', fileNameProperty: 'nomFichier')]
    private ?File $file = null;

    // Nom du fichier stocké (nullable pour permettre la suppression)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomFichier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $page = null; // page d'affichage: recette, produit, producteurice, ressource

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $role = null; // photo_principale, photo_supplementaire, fichier, video, logo

    //---------------- r e l a t i o n s  ManyToOne
    #[ORM\ManyToOne(targetEntity: Recette::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Recette $recette = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(targetEntity: Producteurice::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Producteurice $producteurice = null;

    #[ORM\ManyToOne(targetEntity: Ressource::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Ressource $ressource = null;

    // ---------------- GETTERS / SETTERS ----------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }
public function setFile(?File $file): void
{
    if ($file instanceof UploadedFile) {
        $filename = uniqid().'.'.$file->guessExtension();
        $file->move('public/uploads/medias', $filename);
        $this->nomFichier = $filename;
    }

    $this->file = $file;
}

    // public function setFile(?File $file): void
    // {
    //     $this->file = $file;
    // }
//     public function setFile(?File $file): void
// {
//     $this->file = $file;
// }

// public function setFile(UploadedFile $file): void
// {
//     $filename = uniqid().'.'.$file->guessExtension();
//     $file->move('uploads/medias', $filename);
//     $this->nomFichier = $filename;
// }



    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(?string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(?string $page): static
    {
        $this->page = $page;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): static
    {
        $this->recette = $recette;
        if ($recette) {
            $this->page = 'recette';
        }
        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;
        if ($produit) {
            $this->page = 'produit';
        }
        return $this;
    }

    public function getProducteurice(): ?Producteurice
    {
        return $this->producteurice;
    }

    public function setProducteurice(?Producteurice $producteurice): static
    {
        $this->producteurice = $producteurice;
        if ($producteurice) {
            $this->page = 'producteurice';
        }
        return $this;
    }

    public function getRessource(): ?Ressource
    {
        return $this->ressource;
    }

    public function setRessource(?Ressource $ressource): static
    {
        $this->ressource = $ressource;
        if ($ressource) {
            $this->page = 'ressource';
        }
        return $this;
    }
}


// ANCIEN ENTITÉ PRODUCTEURICE
<?php

namespace App\Entity;

use App\Entity\Produit;
use App\Entity\Media;
use App\Repository\ProducteuriceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;


#[ORM\Entity(repositoryClass: ProducteuriceRepository::class)]
class Producteurice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $isCoop = null;

    #[ORM\Column(length: 255)]
    private ?string $site = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lienProduits = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    /** @var UploadedFile|null */
    private ?UploadedFile $logo = null;

    /** @var UploadedFile|null */
    private ?UploadedFile $photoPrincipale = null;

    /** @var UploadedFile[] */
    private array $photosSupplementaires = [];
    
    // “s” obligatoire pour les collections pour variable et inverseBy (Many->s)
    //----------------r e l a t i o n   ManyToMany /côté pas propriétaire = inverse
    /** 
     * @var Collection<int, Produit>
     */
    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'producteurices')]
    private Collection $produits;
    
    //---------------- r e l a t i o n s  OneToMany
    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'producteurice', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $medias;



    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function isCoop(): ?bool
    {
        return $this->isCoop;
    }

    public function setIsCoop(bool $isCoop): static
    {
        $this->isCoop = $isCoop;
        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): static
    {
        $this->site = $site;
        return $this;
    }

    public function getLienProduits(): ?string
    {
        return $this->lienProduits;
    }

    public function setLienProduits(?string $lienProduits): static
    {
        $this->lienProduits = $lienProduits;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->addProducteurice($this); // synchronisation ManyToMany
        }
        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeProducteurice($this); // synchronisation ManyToMany
        }
        return $this;
    }

    public function getNomProduits(): string
    {
        if ($this->produits->isEmpty()) {
            return '—';
        }

        return implode(', ', $this->produits
            ->map(fn(Produit $p) => $p->getNom())
            ->toArray()
        );
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): static
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setProducteurice($this); // synchronisation OneToMany
        }
        return $this;
    }

    public function removeMedia(Media $media): static
    {
        if ($this->medias->removeElement($media)) {
            if ($media->getProducteurice() === $this) {
                $media->setProducteurice(null); // synchronisation OneToMany
            }
        }
        return $this;
    }

    public function getLogoMedia(): ?Media // récupérer le logo (ROLE='logo')
    {
        return $this->medias
            ->filter(fn (Media $m) => $m->getRole() === 'logo')
            ->first() ?: null;
    }

    public function getLogoMediaPath(): ?string
    {
        $logo = $this->getLogoMedia();
        return $logo ? $logo->getNomFichier() : null;
    }


    // miniatures photo/logo
    public function getLogo(): ?UploadedFile
    {
        return $this->logo;
    }

    public function setLogo(?UploadedFile $logo): self
    {
        $this->logo = $logo;
        return $this;
    }

    public function getPhotoPrincipale(): ?UploadedFile
    {
        return $this->photoPrincipale;
    }

    public function setPhotoPrincipale(?UploadedFile $photoPrincipale): self
    {
        $this->photoPrincipale = $photoPrincipale;
        return $this;
    }

    public function getPhotoPrincipalePath(): ?string
    {
        $photo = $this->medias
            ->filter(fn (Media $m) => $m->getRole() === 'photo_principale')
            ->first();

        return $photo ? $photo->getNomFichier() : null;
    }

    public function getPhotosSupplementaires(): array
    {
        return $this->photosSupplementaires;
    }

    public function setPhotosSupplementaires(array $photos): self
    {
        $this->photosSupplementaires = $photos;
        return $this;
    }
    
    //pour field plusieurs miniatures avec création de 
    // templates/admin/fields/photos_supplementaires.html.twig    
    public function getPhotosSupplementairesPaths(): array
    {
        return $this->medias
            ->filter(fn (Media $m) => $m->getRole() === 'photo_supplementaire')
            ->map(fn (Media $m) => $m->getNomFichier())
            ->toArray();
    }

    public function generateSlug(): void 
    { 
        if (!$this->nom) { return; } 
        $slug = strtolower(trim($this->nom)); 
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug); 
        $slug = trim($slug, '-'); 
        $this->slug = $slug; 
    }
    
    public function __toString(): string
    {
        return $this->nom ?? 'Producteurice';
    }

}
