<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\PoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PoleRepository::class)]
class Pole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptif = null;

    #[ORM\Column(length: 255, nullable: true)] // avec nullable: true -> on force la base a avoir la colonne descriptif_pdf possiblement NULL, sinon NOT NULL
    private ?string $descriptif_pdf = null;

    #[ORM\Column]
    private ?int $volume_horaire = null;

    //---------------- CHAMPS D’UPLOAD pour champ plusieurs fichiers (non mappés) 
    /** * @var UploadedFile[] */ 
    private array $fichiers = [];

    //----------------r e l a t i o n s  ManyToMany  
    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'pole')]
    private Collection $users;

    /**
     * @var Collection<int, Adhesion>
     */
    #[ORM\ManyToMany(targetEntity: Adhesion::class, mappedBy: 'poles')]
    private Collection $adhesions;

    //---------------- r e l a t i o n s  OneToMany
    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'pole', cascade: ['persist', 'remove'], orphanRemoval: true )]
    private Collection $medias;

    //---------------- c o n s t r u c t e u r
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->adhesions = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): static
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getDescriptifPdf(): ?string
    {
        return $this->descriptif_pdf;
    }

    public function setDescriptifPdf(string $descriptif_pdf): static
    {
        $this->descriptif_pdf = $descriptif_pdf;

        return $this;
    }

    public function getVolumeHoraire(): ?int
    {
        return $this->volume_horaire;
    }

    public function setVolumeHoraire(int $volume_horaire): static
    {
        $this->volume_horaire = $volume_horaire;

        return $this;
    }

        public function getFichiers(): array 
    { 
        return $this->fichiers; 
    } 
    public function setFichiers(array $fichiers): self 
    { 
        $this->fichiers = $fichiers; 
        return $this; 
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addPole($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removePole($this);
        }

        return $this;
    }

    public function getAdhesions(): Collection
    {
        return $this->adhesions;
    }

    public function addAdhesion(Adhesion $adhesion): self
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions->add($adhesion);
            $adhesion->addPole($this);
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): self
    {
        if ($this->adhesions->removeElement($adhesion)) {
            $adhesion->removePole($this);
        }

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
            $media->setPole($this); 
        } 
        return $this; 
    } 
    
    public function removeMedia(Media $media): static 
    { 
        if ($this->medias->removeElement($media)) { 
            if ($media->getPole() === $this) { 
                $media->setPole(null);  
            } 
        } 
        return $this; 
    }
}
