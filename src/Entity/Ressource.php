<?php

namespace App\Entity;

use App\Entity\Categorie;
use App\Entity\Pole;
use App\Entity\User;    
use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datePublication = null;

    #[ORM\Column(length: 150)]
    #[Assert\Length(max: 150, maxMessage: 'Titre trop long (150 max)')]
    private ?string $titre = null;

    #[ORM\Column(length: 200)]
    #[Assert\Length(max: 200, maxMessage: 'Sous-titre trop long (200 max)')]
    private ?string $sousTitre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ressourceTexte = null;
    
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $lienExterne1 = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $lienExterne2 = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $lienExterne3 = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'non_validée'; 
    // choix multiple pour Admin, avec dans RessourceCrudController : 
    // ChoiceField::new('statut')
    // ->setLabel('Statut')
    // ->setChoices([
    //     'Non validée' => 'non_validée',
    //     'Publiée'     => 'publiée',
    //     'Archivée'    => 'archivée',
    // ]);

    //----------------r e l a t i o n s  ManyToOne
    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne]
    private ?Pole $pole = null;

    #[ORM\ManyToOne(inversedBy: 'ressource')]
    private ?User $user = null;

    //----------------r e l a t i o n s  OneToMany
    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'ressource')]
    private Collection $medias;




    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePublication(): ?\DateTimeImmutable
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeImmutable $datePublication): self
    {
        $this->datePublication = $datePublication;

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
    
        public function getLienExterne1(): ?string
    {
        return $this->lienExterne1;
    }

    public function setLienExterne1(?string $lienExterne1): static
    {
        $this->lienExterne1 = $lienExterne1;

        return $this;
        
    }

    public function getLienExterne2(): ?string
    {
        return $this->lienExterne2;
    }

    public function setLienExterne2(?string $lienExterne2): static
    {
        $this->lienExterne2 = $lienExterne2;

        return $this;
    }

    public function getLienExterne3(): ?string
    {
        return $this->lienExterne3;
    }

    public function setLienExterne3(?string $lienExterne3): static
    {
        $this->lienExterne3 = $lienExterne3;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

public function setStatut(string $statut): self
{
    $this->statut = $statut;

    if ($statut === 'publiée') {
        $this->datePublication = new \DateTimeImmutable();
    }

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

    /**
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): static
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setRessource($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): static
    {
        if ($this->medias->removeElement($media)) {
            if ($media->getRessource() === $this) {
                $media->setRessource(null);
            }
        }

        return $this;
    }
}