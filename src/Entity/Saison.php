<?php

namespace App\Entity;

use App\Entity\Adhesion;
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

    // début de saison : 1er septembre
    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateDebut = null;

    // Fin de saison : 31 août
    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateFin = null;

    //---------------- r e l a t i o n s  OneToMany
    #[ORM\OneToMany(mappedBy: 'saison', targetEntity: Adhesion::class, cascade: ['remove'])]
    private Collection $adhesions;


    
    public function __construct()
    {
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

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

    public function removeAdhesion(Adhesion $adhesion): static
    {
        $this->adhesions->removeElement($adhesion);
        return $this;
    }

    public function countAdhesions(): int
    {
        return $this->adhesions->count();
    }

    public function __toString(): string
    {
        return $this->nom ?? 'Saison';
    }
}
