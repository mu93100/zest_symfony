<?php

namespace App\Entity;

use App\Entity\Producteurice;
use App\Entity\Recette;
use App\Entity\Produit;
use App\Entity\Ressource;
use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[Vich\Uploadable]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // upload simple A VOIR POUR R É A C T I V E R avec VichUploaderBundle :
    // use Vich\UploaderBundle\Mapping\Annotation as Vich;
    // activer le bundle VichUploader pour uploader TOUT format de fichier
    // terminal : composer require vich/uploader-bundle / pas de colonne file en BDD
    // et création de src/EventListener/MediaMultipleUploadSubscriber.php
    // #[Vich\UploadableField(mapping: 'medias', fileNameProperty: 'nomFichier')]
    // Fichier uploadé (non mappé en BDD)
    #[Vich\UploadableField(mapping: 'medias', fileNameProperty: 'nomFichier')]
    private ?File $file = null;

    #[ORM\Column(length: 255, nullable: true)] // nullable = pour permettre la suppression
    private ?string $nomFichier = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $page = null; // page d'affichage: recette, produit, producteurice, ressource

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $role = null; // photo_principale, photo_supplementaire, fichier, video, logo

    #[ORM\Column(nullable: true)] // obligatoire avec VICH
    private ?\DateTimeImmutable $updatedAt = null;

    // ---------------- r e l a t i o n s  ManyToOne
    // OU      #[ORM\ManyToOne(targetEntity: Producteurice::class, inversedBy: 'medias')]
    #[ORM\ManyToOne(targetEntity: Producteurice::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Producteurice $producteurice = null;

    #[ORM\ManyToOne(targetEntity: Recette::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Recette $recette = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(targetEntity: Ressource::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Ressource $ressource = null;

    #[ORM\ManyToOne(inversedBy: 'medias')] 
    private ?Pole $pole = null;

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
        $this->file = $file;
        if ($file !== null) {
            $this->updatedAt = new \DateTimeImmutable();
        } 
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(?string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;

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

    public function getPole(): ?Pole 
    { 
        return $this->pole; 
    } 
    
    public function setPole(?Pole $pole): static 
    { 
        $this->pole = $pole; return $this; 
    }

    public function __toString(): string
    {
        return $this->nomFichier ?? 'Media';
    }
}
