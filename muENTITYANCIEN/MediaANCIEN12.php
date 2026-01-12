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

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Fichier uploadé (non mappé en BDD)
    private ?File $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomFichier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $page = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $role = null;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    { dump('SETFILE', $file); // ← TEMPORAIRE
        if ($file instanceof UploadedFile) {
            $filename = uniqid().'.'.$file->guessExtension();
            $file->move('public/uploads/medias', $filename);
            $this->nomFichier = $filename;
        }

        $this->file = $file;
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
