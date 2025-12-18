<?php

namespace App\Entity;

use App\Repository\AdhesionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AdhesionRepository::class)]
#[UniqueEntity(
    fields: ['user', 'saison'],
    message: 'Vous avez déjà une adhésion pour cette saison.'
)]
class Adhesion 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateAdhesion;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $attentesTexte = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $competencesTexte = null;

    // paiement validé par admin
    #[ORM\Column(type: 'boolean')]
    private bool $paiement = false;

    //----------------r e l a t i o n s  ManyToOne
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'adhesions')]
    private ?User $user = null;
    // relation ManyToOne car un user aura plusieurs adhesions :: une par saison

    #[ORM\ManyToOne(targetEntity: Groupe::class, inversedBy: 'adhesions')]
    private ?Groupe $groupe = null;

    #[ORM\ManyToOne(targetEntity: MontantAdhesion::class )]
    private ?MontantAdhesion $montantAdhesion = null;

    #[ORM\ManyToOne(targetEntity: Saison::class, inversedBy: 'adhesions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Saison $saison = null;

    //----------------r e l a t i o n s  ManyToMany
    // #[ORM\ManyToMany(targetEntity: Motivation::class)]
    // private Collection $motivations;
    #[ORM\ManyToMany(targetEntity: Motivation::class, inversedBy: 'adhesionMotiv')]
    private Collection $motivations;

    #[ORM\ManyToMany(targetEntity: Dispo::class, inversedBy: 'adhesions')]
    private Collection $dispos;

    #[ORM\ManyToMany(targetEntity: Pole::class)]
    private Collection $poles;



    public function __construct()
    {
        $this->motivations = new ArrayCollection();
        $this->dispos = new ArrayCollection();
        $this->poles = new ArrayCollection();
        $this->dateAdhesion = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaison(): ?Saison
    {
        return $this->saison;
    }

    public function setSaison(Saison $saison): static
    {
        $this->saison = $saison;
        return $this;
    }

    public function getDateAdhesion(): \DateTimeInterface
    {
        return $this->dateAdhesion;
    }

    public function setDateAdhesion(\DateTimeInterface $dateAdhesion): self
    {
        $this->dateAdhesion = $dateAdhesion;

        return $this;
    }

    public function getAttentesTexte(): ?string
    {
        return $this->attentesTexte;
    }   
    public function setAttentesTexte(?string $attentesTexte): self
    {
        $this->attentesTexte = $attentesTexte;

        return $this;
    }

    public function getCompetencesTexte(): ?string
    {
        return $this->competencesTexte;
    }

    public function setCompetencesTexte(?string $competencesTexte): self
    {
        $this->competencesTexte = $competencesTexte;

        return $this;
    }

    public function isPaiement(): bool
    {
        return $this->paiement;
    }

    public function setPaiement(bool $paiement): self
    {
        $this->paiement = $paiement;

        return $this;
    }
// on récupère le user connecté
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, Motivation>
     */

    public function getMotivations(): Collection
    {
        return $this->motivations;
    }

    public function addMotivation(Motivation $motivation): self
    {
        if (!$this->motivations->contains($motivation)) {
            $this->motivations->add($motivation);
        }

        return $this;
    }

    public function removeMotivation(Motivation $motivation): self
    {
        $this->motivations->removeElement($motivation);

        return $this;
    }

    /**
     * @return Collection<int, Dispo>
     */
    public function getDispos(): Collection
    {
        return $this->dispos;
    }

    public function addDispo(Dispo $dispo): self
    {
        if (!$this->dispos->contains($dispo)) {
            $this->dispos->add($dispo);
        }

        return $this;
    }

    public function removeDispo(Dispo $dispo): self
    {
        $this->dispos->removeElement($dispo);

        return $this;
    }

    /**
     * @return Collection<int, Pole>
     */
    public function getPoles(): Collection
    {
        return $this->poles;
    }

    public function addPole(Pole $pole): self
    {
        if (!$this->poles->contains($pole)) {
            $this->poles->add($pole);
        }

        return $this;
    }

    public function removePole(Pole $pole): self
    {
        $this->poles->removeElement($pole);

        return $this;
    }

    public function getMontantAdhesion(): ?MontantAdhesion 
    { 
        return $this->montantAdhesion; 
    }

    public function setMontantAdhesion(?MontantAdhesion $montant): self 
    { 
        $this->montantAdhesion = $montant; 
        
        return $this; 
    }

}