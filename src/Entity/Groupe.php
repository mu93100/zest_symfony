<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $nom = null;

    #[ORM\Column(length: 45)]
    private ?string $ville = null;

    #[ORM\Column]
    private ?bool $is_referent = false;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $referentNom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $referentEmail = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $referentTelephone = null;

    #[ORM\Column]
    private ?bool $is_groupe_open = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

//----------------r e l a t i o n s OneToMany
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'groupe')]
    private Collection $membres;
//----------------

    public function __construct()
    {
        $this->membres = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function isReferent(): ?bool
    {
        return $this->is_referent;
    }

    public function setIsReferent(bool $is_referent): static
    {
        $this->is_referent = $is_referent;

        return $this;
    }

        public function getReferentNom(): ?string
    {
        return $this->referentNom;
    }

    public function setReferentNom(?string $referentNom): static
    {
        $this->referentNom = $referentNom;
        return $this;
    }

    public function getReferentEmail(): ?string
    {
        return $this->referentEmail;
    }

    public function setReferentEmail(?string $referentEmail): static
    {
        $this->referentEmail = $referentEmail;
        return $this;
    }

    public function getReferentTelephone(): ?string
    {
        return $this->referentTelephone;
    }

    public function setReferentTelephone(?string $referentTelephone): static
    {
        $this->referentTelephone = $referentTelephone;
        return $this;
    }

    public function isGroupeOpen(): ?bool 
    {
        return $this->is_groupe_open;
    }

    public function setIsGroupeOpen(bool $is_groupe_open): static
    {
        $this->is_groupe_open = $is_groupe_open;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
//----------------r e l a t i o n s  OneToMany  
// --------------- pour que le groupe (nom) soit affiché dans colonne groupe de user 
    public function __toString(): string
    {
        return $this->nom ?? 'Groupe';
    }
// ----------------
    /** @return Collection<int, User> */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(User $membre): static
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
            $membre->setGroupe($this);
        }
        return $this;
    }

    public function removeMembre(User $membre): static
    {
        if ($this->membres->removeElement($membre)) {
            if ($membre->getGroupe() === $this) {
                $membre->setGroupe(null);
            }
        }
        return $this;
    } 
}
