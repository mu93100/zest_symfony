<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    /**
     * @var Collection<int, Producteurice>
     */
    #[ORM\ManyToMany(targetEntity: Producteurice::class, mappedBy: 'produit')]
    private Collection $producteurices;

    public function __construct()
    {
        $this->producteurices = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Producteurice>
     */
    public function getProducteurices(): Collection
    {
        return $this->producteurices;
    }

    public function addProducteurice(Producteurice $producteurice): static
    {
        if (!$this->producteurices->contains($producteurice)) {
            $this->producteurices->add($producteurice);
            $producteurice->addProduit($this);
        }

        return $this;
    }

    public function removeProducteurice(Producteurice $producteurice): static
    {
        if ($this->producteurices->removeElement($producteurice)) {
            $producteurice->removeProduit($this);
        }

        return $this;
    }
}
