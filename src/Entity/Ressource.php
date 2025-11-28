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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;


    #[ORM\ManyToOne]
    private ?Pole $pole = null;

    #[ORM\ManyToOne(inversedBy: 'ressource')]
    private ?User $user = null;

    #[ORM\OneToOne(inversedBy: 'photo_principale', cascade: ['persist', 'remove'])]
    private ?Photos $photo_principale = null;

    /**
     * @var Collection<int, Photos>
     */
    #[ORM\OneToMany(targetEntity: Photos::class, mappedBy: 'photos_supp')]
    private Collection $photos_supp;

    #[ORM\Column]
    private ?bool $is_publication = null;

    public function __construct()
    {
        $this->photos_supp = new ArrayCollection();
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPole(): ?Pole
    {
        return $this->pole;
    }

    public function setPole(?Pole $pole): static
    {
        $this->pole = $pole;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPhotoPrincipale(): ?Photos
    {
        return $this->photo_principale;
    }

    public function setPhotoPrincipale(?Photos $photo_principale): static
    {
        $this->photo_principale = $photo_principale;

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotosSupp(): Collection
    {
        return $this->photos_supp;
    }

    public function addPhotosSupp(Photos $photosSupp): static
    {
        if (!$this->photos_supp->contains($photosSupp)) {
            $this->photos_supp->add($photosSupp);
            $photosSupp->setPhotosSupp($this);
        }

        return $this;
    }

    public function removePhotosSupp(Photos $photosSupp): static
    {
        if ($this->photos_supp->removeElement($photosSupp)) {
            // set the owning side to null (unless already changed)
            if ($photosSupp->getPhotosSupp() === $this) {
                $photosSupp->setPhotosSupp(null);
            }
        }

        return $this;
    }

    public function isPublication(): ?bool
    {
        return $this->is_publication;
    }

    public function setIsPublication(bool $is_publication): static
    {
        $this->is_publication = $is_publication;

        return $this;
    }
}
