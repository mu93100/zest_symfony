<?php

namespace App\Entity;

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

    // --- CHAMPS D’UPLOAD (non mappés) ---
    private ?UploadedFile $logo = null;
    private ?UploadedFile $photoPrincipale = null;
    private array $photosSupplementaires = [];

    // --- RELATION MEDIA ---
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'producteurice', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $medias;

    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'producteurices')]
    private Collection $produits;

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

    // --- PRODUITS ---
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->addProducteurice($this);
        }
        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeProducteurice($this);
        }
        return $this;
    }

    public function getNomProduits(): string
    {
        if ($this->produits->isEmpty()) {
            return '—';
        }

        return implode(', ', $this->produits
            ->map(fn (Produit $p) => $p->getNom())
            ->toArray()
        );
    }

    // --- MEDIAS ---
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): static
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setProducteurice($this);
        }
        return $this;
    }

    public function removeMedia(Media $media): static
    {
        if ($this->medias->removeElement($media)) {
            if ($media->getProducteurice() === $this) {
                $media->setProducteurice(null);
            }
        }
        return $this;
    }

    // --- GETTERS POUR EASYADMIN ---

    public function getLogoMediaPath(): ?string
    {
        $m = $this->medias->filter(fn(Media $m) => $m->getRole() === 'logo')->first();
        return $m ? $m->getNomFichier() : null;
    }

    public function getPhotoPrincipalePath(): ?string
    {
        $m = $this->medias->filter(fn(Media $m) => $m->getRole() === 'photo_principale')->first();
        return $m ? $m->getNomFichier() : null;
    }

    public function getPhotosSupplementairesPaths(): array
    {
        return $this->medias
            ->filter(fn(Media $m) => $m->getRole() === 'photo_supplementaire')
            ->map(fn(Media $m) => $m->getNomFichier())
            ->toArray();
    }

    // --- CHAMPS UPLOAD ---

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

    public function getPhotosSupplementaires(): array
    {
        return $this->photosSupplementaires;
    }

    public function setPhotosSupplementaires(array $photos): self
    {
        $this->photosSupplementaires = $photos;
        return $this;
    }

    public function generateSlug(): void
    {
        if (!$this->nom) {
            return;
        }

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
