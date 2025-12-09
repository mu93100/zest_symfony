<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SaisonRepository::class)]
class Saison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9, unique: true)]
    private ?string $nom = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    //----------------r e l a t i o n s  OneToMany
    #[ORM\OneToMany(mappedBy: 'saison', targetEntity: Adhesion::class, cascade: ['remove'])]
    private Collection $adhesions;


    
    public function __construct(string $nom)
    {
        $this->nom = $nom;
        $this->dateCreation = new \DateTimeImmutable(); // auto à la création
        $this->adhesions = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    // 🔹 Relation avec Adhesion
    public function getAdhesions(): Collection
    {
        return $this->adhesions;
    }

    public function addAdhesion(Adhesion $adhesion): static
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions->add($adhesion);
            $adhesion->setSaison($this);
        }
        return $this;
    }

    // removeAdhesion() → enlève une adhésion de cette collection.
// 👉 En clair : c’est une méthode utilitaire pour gérer la relation bidirectionnelle. Elle permet de maintenir la cohérence entre les deux côtés de la relation (Saison et Adhesion)
    public function removeAdhesion(Adhesion $adhesion): static
    {
        $this->adhesions->removeElement($adhesion);
        return $this;
    }

    // 🔹 Compteur d’adhésions
    public function countAdhesions(): int
    {
        return $this->adhesions->count();
    }

    
}
