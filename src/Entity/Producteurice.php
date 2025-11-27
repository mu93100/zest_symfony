<?php

namespace App\Entity;

use App\Repository\ProducteuriceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProducteuriceRepository::class)]
class Producteurice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $produits = null;

    #[ORM\Column]
    private ?bool $is_coop = null;

    #[ORM\Column(length: 255)]
    private ?string $site = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lien_produits = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, produit>
     */
    #[ORM\ManyToMany(targetEntity: produit::class, inversedBy: 'producteurices')]
    private Collection $produit;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
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

    public function getProduits(): ?string
    {
        return $this->produits;
    }

    public function setProduits(string $produits): static
    {
        $this->produits = $produits;

        return $this;
    }

    public function isCoop(): ?bool
    {
        return $this->is_coop;
    }

    public function setIsCoop(bool $is_coop): static
    {
        $this->is_coop = $is_coop;

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
        return $this->lien_produits;
    }

    public function setLienProduits(?string $lien_produits): static
    {
        $this->lien_produits = $lien_produits;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

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

    /**
     * @return Collection<int, produit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(produit $produit): static
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
        }

        return $this;
    }

    public function removeProduit(produit $produit): static
    {
        $this->produit->removeElement($produit);

        return $this;
    }
}
