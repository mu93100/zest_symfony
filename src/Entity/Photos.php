<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    //----------------r e l a t i o n s  OneToOne
    #[ORM\OneToOne(mappedBy: 'photoPrincipale', cascade: ['persist', 'remove'])]
    private ?Ressource $ressourcePrincipale = null;

    //----------------r e l a t i o n s  ManyToOne
    #[ORM\ManyToOne(inversedBy: 'photosSupp')]
    private ?Ressource $ressource = null;


    public function getId(): ?int
    {
        return $this->id;
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

    // --- Lien vers la ressource principale ---
    public function getRessourcePrincipale(): ?Ressource
    {
        return $this->ressourcePrincipale;
    }

    public function setRessourcePrincipale(?Ressource $ressource): self
    {
        $this->ressourcePrincipale = $ressource;
        return $this;
    }

    // --- Lien vers la ressource (photos supplémentaires) ---
    public function getRessource(): ?Ressource
    {
        return $this->ressource;
    }

    public function setRessource(?Ressource $ressource): self
    {
        $this->ressource = $ressource;
        return $this;
    }
}
