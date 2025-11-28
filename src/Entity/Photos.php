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

    #[ORM\OneToOne(mappedBy: 'photo_principale', cascade: ['persist', 'remove'])]
    private ?Ressource $photo_principale = null;

    #[ORM\ManyToOne(inversedBy: 'photos_supp')]
    private ?Ressource $photos_supp = null;

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
        return $this->photo_principale;
    }

    public function setPhotoPrincipale(?Ressource $photo_principale): static
    {
        // unset the owning side of the relation if necessary
        if ($photo_principale === null && $this->photo_principale !== null) {
            $this->photo_principale->setPhotoPrincipale(null);
        }

        // set the owning side of the relation if necessary
        if ($photo_principale !== null && $photo_principale->getPhotoPrincipale() !== $this) {
            $photo_principale->setPhotoPrincipale($this);
        }

        $this->photo_principale = $photo_principale;

        return $this;
    }

    public function getPhotosSupp(): ?Ressource
    {
        return $this->photos_supp;
    }

    public function setPhotosSupp(?Ressource $photos_supp): static
    {
        $this->photos_supp = $photos_supp;

        return $this;
    }
}
