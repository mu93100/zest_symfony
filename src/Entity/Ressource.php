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
    private ?string $sousTitre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ressourceTexte = null;

    //----------------r e l a t i o n s  ManyToOne
    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;


    #[ORM\ManyToOne]
    private ?Pole $pole = null;

    #[ORM\ManyToOne(inversedBy: 'ressource')]
    private ?User $user = null;

    #[ORM\OneToOne(inversedBy: 'photoPrincipale', cascade: ['persist', 'remove'])]
    private ?Photos $photoPrincipale = null;

    /**
     * @var Collection<int, Photos>
     */
    #[ORM\OneToMany(targetEntity: Photos::class, mappedBy: 'photosSupp')]
    private Collection $photosSupp;

    #[ORM\Column]
    private ?bool $isPublication = null;

    public function __construct()
    {
        $this->photosSupp = new ArrayCollection();
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
        return $this->sousTitre;
    }

    public function setSousTitre(?string $sousTitre): static
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    public function getRessourceTexte(): ?string
    {
        return $this->ressourceTexte;
    }

    public function setRessourceTexte(string $ressourceTexte): static
    {
        $this->ressourceTexte = $ressourceTexte;

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
        return $this->photoPrincipale;
    }

    public function setPhotoPrincipale(?Photos $photoPrincipale): static
    {
        $this->photoPrincipale = $photoPrincipale;

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotosSupp(): Collection
    {
        return $this->photosSupp;
    }

    public function addPhotosSupp(Photos $photosSupp): static
    {
        if (!$this->photosSupp->contains($photosSupp)) {
            $this->photosSupp->add($photosSupp);
            $photosSupp->setPhotosSupp($this);
        }

        return $this;
    }

    public function removePhotosSupp(Photos $photosSupp): static
    {
        if ($this->photosSupp->removeElement($photosSupp)) {
            // set the owning side to null (unless already changed)
            if ($photosSupp->getPhotosSupp() === $this) {
                $photosSupp->setPhotosSupp(null);
            }
        }

        return $this;
    }

    public function isPublication(): ?bool
    {
        return $this->isPublication;
    }

    public function setIsPublication(bool $isPublication): static
    {
        $this->isPublication = $isPublication;

        return $this;
    }
}
