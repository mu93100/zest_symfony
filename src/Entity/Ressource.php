<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 300)]
    private ?string $titre = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $sous_titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ressource_texte = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $photos_supp = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?categorie $categorie = null;

    /**
     * @var Collection<int, photos>
     */
    #[ORM\ManyToMany(targetEntity: photos::class, inversedBy: 'ressources')]
    private Collection $photos;

    #[ORM\ManyToOne]
    private ?pole $pole = null;

    #[ORM\ManyToOne(inversedBy: 'ressource')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteurice = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSousTitre(): ?string
    {
        return $this->sous_titre;
    }

    public function setSousTitre(?string $sous_titre): static
    {
        $this->sous_titre = $sous_titre;

        return $this;
    }

    public function getRessourceTexte(): ?string
    {
        return $this->ressource_texte;
    }

    public function setRessourceTexte(string $ressource_texte): static
    {
        $this->ressource_texte = $ressource_texte;

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

    public function getPhotosSupp(): ?string
    {
        return $this->photos_supp;
    }

    public function setPhotosSupp(string $photos_supp): static
    {
        $this->photos_supp = $photos_supp;

        return $this;
    }


    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, photos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(photos $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
        }

        return $this;
    }

    public function removePhoto(photos $photo): static
    {
        $this->photos->removeElement($photo);

        return $this;
    }

    public function getPole(): ?pole
    {
        return $this->pole;
    }

    public function setPole(?pole $pole): static
    {
        $this->pole = $pole;

        return $this;
    }

    public function getAuteurice(): ?User
    {
        return $this->auteurice;
    }

    public function setAuteurice(?User $auteurice): static
    {
        $this->auteurice = $auteurice;

        return $this;
    }
}
