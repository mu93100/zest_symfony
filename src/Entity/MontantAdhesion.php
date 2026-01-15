<?php

namespace App\Entity;

use App\Entity\Adhesion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\MontantAdhesionRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MontantAdhesionRepository::class)]
class MontantAdhesion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    #[ORM\OneToMany(targetEntity: Adhesion::class, mappedBy: 'montantAdhesion')]
    private Collection $adhesions;



    public function __construct()
    {
        $this->adhesions = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
    /**
     * @return Collection<int, Adhesion>
     */
    public function getAdhesions(): Collection
    {
        return $this->adhesions;        
    }   

    public function addAdhesion(Adhesion $adhesion): self
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions->add($adhesion);
            $adhesion->setMontantAdhesion($this);
        }

        return $this;
    }   

    public function removeAdhesion(Adhesion $adhesion): self
    {
        if ($this->adhesions->removeElement($adhesion)) {
            // set the owning side to null (unless already changed)
            if ($adhesion->getMontantAdhesion() === $this) {
                $adhesion->setMontantAdhesion(null);
            }
        }

        return $this;
    }  
    public function __toString(): string
    {
        return $this->montant ?? 'Montant non défini';
    }     
}