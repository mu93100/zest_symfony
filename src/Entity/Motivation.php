<?php

namespace App\Entity;

use App\Repository\MotivationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotivationRepository::class)]
class Motivation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

   //----------------r e l a t i o n s  ManyToMany   
    /**
     * @var Collection<int, Adhesion>
     */
    #[ORM\ManyToMany(targetEntity: Adhesion::class, mappedBy: 'motivation')]
    private Collection $adhesionMotiv;



    public function __construct()
    {
        $this->adhesionMotiv = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function getAdhesionMotiv(): Collection
    {
        return $this->adhesionMotiv;
    }

    public function addAdhesionMotiv(Adhesion $adhesionMotiv): static
    {
        if (!$this->adhesionMotiv->contains($adhesionMotiv)) {
            $this->adhesionMotiv->add($adhesionMotiv);
            $adhesionMotiv->addMotivation($this);
        }

        return $this;
    }

    public function removeAdhesionMotiv(Adhesion $adhesionMotiv): static
    {
        if ($this->adhesionMotiv->removeElement($adhesionMotiv)) {
            $adhesionMotiv->removeMotivation($this);
        }

        return $this;
    }
}
