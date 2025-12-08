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

    #[ORM\OneToOne(mappedBy: 'photoPrincipale', cascade: ['persist', 'remove'])]
    private ?Ressource $photoPrincipale = null;

    #[ORM\ManyToOne(inversedBy: 'photosSupp')]
    private ?Ressource $photosSupp = null;

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

    public function getPhotoPrincipale(): ?Ressource
    {
        return $this->photoPrincipale;
    }

    public function setPhotoPrincipale(?Ressource $photoPrincipale): static
    {
        // unset the owning side of the relation if necessary
        if ($photoPrincipale === null && $this->photoPrincipale !== null) {
            $this->photoPrincipale->setPhotoPrincipale(null);
        }

        // set the owning side of the relation if necessary
        if ($photoPrincipale !== null && $photoPrincipale->getPhotoPrincipale() !== $this) {
            $photoPrincipale->setPhotoPrincipale($this);
        }

        $this->photoPrincipale = $photoPrincipale;

        return $this;
    }

    public function getPhotosSupp(): ?Ressource
    {
        return $this->photosSupp;
    }

    public function setPhotosSupp(?Ressource $photosSupp): static
    {
        $this->photosSupp = $photosSupp;

        return $this;
    }
}
