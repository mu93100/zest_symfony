<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Referent;
use App\Entity\Adhesion;
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

    #[ORM\Column(length: 45, nullable: false)]
    private string $nom;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseDistrib = null;

    #[ORM\Column(length: 45)]
    private ?string $ville = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isReferent = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isOpen = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $dateCreation = null;

    //----------------r e l a t i o n s OneToMany
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'groupe')]
    private Collection $membres;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: Adhesion::class)]
    private Collection $adhesions;

    /**
     * @var Collection<int, Referent>
     */
    #[ORM\OneToMany(targetEntity: Referent::class, mappedBy: 'groupe')]
    private Collection $referents;




    public function __construct()
    {
        $this->membres = new ArrayCollection();
        $this->dateCreation = new \DateTimeImmutable();
        $this->adhesions = new ArrayCollection();
        $this->referents = new ArrayCollection();
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

    public function getAdresseDistrib(): ?string
    {
        return $this->adresseDistrib;
    }

    public function setAdresseDistrib(?string $adresseDistrib): static
    {
        $this->adresseDistrib = $adresseDistrib;

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
        return $this->isReferent;
    }

    public function setIsReferent(bool $isReferent): static
    {
        $this->isReferent = $isReferent;

        return $this;
    }

    // Fo qui renvoie le user référent du groupe (ou null si aucun).
    public function getReferent(): ?User
    {
        foreach ($this->membres as $membre) {
            if ($membre->isReferent()) {
                return $membre;
            }
        }
        return null;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): static
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
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

    /** @return Collection<int, Adhesion> */
    public function getAdhesions(): Collection
    {
        return $this->adhesions;
    }

    public function addAdhesion(Adhesion $adhesion): static
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions->add($adhesion);
            $adhesion->setGroupe($this);
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): static
    {
        if ($this->adhesions->removeElement($adhesion)) {
            if ($adhesion->getGroupe() === $this) {
                $adhesion->setGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Referent>
     */
    public function getReferents(): Collection
    {
        return $this->referents;
    }

    public function addReferent(Referent $referent): static
    {
        if (!$this->referents->contains($referent)) {
            $this->referents->add($referent);
            $referent->setGroupe($this);
        }

        return $this;
    }

    public function removeReferent(Referent $referent): static
    {
        if ($this->referents->removeElement($referent)) {
            // set the owning side to null (unless already changed)
            if ($referent->getGroupe() === $this) {
                $referent->setGroupe(null);
            }
        }

        return $this;
    }
}
