<?php

namespace App\Entity;

use App\Repository\DispoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DispoRepository::class)]
class Dispo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200 )]
    private ?string $libelleDispo = null;

    /**
     * @var Collection<int, Adhesion>
     */
   //----------------r e l a t i o n s  ManyToMany  
    #[ORM\ManyToMany(targetEntity: Adhesion::class, mappedBy: 'dispos')]
    private Collection $adhesions;



    public function __construct()
    {
        $this->adhesions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleDispo(): ?string
    {
        return $this->libelleDispo;
    }

    public function setLibelleDispo(string $libelleDispo): static
    {
        $this->libelleDispo = $libelleDispo;

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
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): self
    {
        $this->adhesions->removeElement($adhesion);

        return $this;
    }
}
