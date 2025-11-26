<?php

namespace App\Entity;

use App\Repository\UsermRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsermRepository::class)]
class Userm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $nom = null;

    #[ORM\Column(length: 45)]
    private ?string $prenom = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mot_de_passe = null;

    #[ORM\Column]
    private ?bool $is_admin = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $code_postal = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_de_naissance = null;

    #[ORM\Column(nullable: true)]
    private ?int $composition_foyer = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombre_enfants = null;

    #[ORM\Column]
    private ?bool $is_referent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motivations_attentes = null;

    #[ORM\Column]
    private ?int $participation_dispo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $competences = null;

    #[ORM\Column]
    private ?int $montant_adhesion = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurmmm222222s')]
    private ?groupe $groupe = null;

    /**
     * @var Collection<int, recette>
     */
    #[ORM\OneToMany(targetEntity: recette::class, mappedBy: 'userm')]
    private Collection $recette;

    /**
     * @var Collection<int, pole>
     */
    #[ORM\ManyToMany(targetEntity: pole::class, inversedBy: 'membres')]
    private Collection $pole;

    /**
     * @var Collection<int, ressource>
     */
    #[ORM\OneToMany(targetEntity: ressource::class, mappedBy: 'auteurice')]
    private Collection $ressources;

    /**
     * @var Collection<int, motivation>
     */
    #[ORM\ManyToMany(targetEntity: motivation::class, inversedBy: 'userm')]
    private Collection $motivation;

    public function __construct()
    {
        $this->recette = new ArrayCollection();
        $this->pole = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->motivation = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): static
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): static
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(int $code_postal): static
    {
        $this->code_postal = $code_postal;

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

    public function getDateDeNaissance(): ?\DateTime
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(?\DateTime $date_de_naissance): static
    {
        $this->date_de_naissance = $date_de_naissance;

        return $this;
    }

    public function getCompositionFoyer(): ?int
    {
        return $this->composition_foyer;
    }

    public function setCompositionFoyer(?int $composition_foyer): static
    {
        $this->composition_foyer = $composition_foyer;

        return $this;
    }

    public function getNombreEnfants(): ?int
    {
        return $this->nombre_enfants;
    }

    public function setNombreEnfants(?int $nombre_enfants): static
    {
        $this->nombre_enfants = $nombre_enfants;

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

    public function getMotivationsAttentes(): ?string
    {
        return $this->motivations_attentes;
    }

    public function setMotivationsAttentes(?string $motivations_attentes): static
    {
        $this->motivations_attentes = $motivations_attentes;

        return $this;
    }

    public function getParticipationDispo(): ?int
    {
        return $this->participation_dispo;
    }

    public function setParticipationDispo(int $participation_dispo): static
    {
        $this->participation_dispo = $participation_dispo;

        return $this;
    }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(?string $competences): static
    {
        $this->competences = $competences;

        return $this;
    }

    public function getMontantAdhesion(): ?int
    {
        return $this->montant_adhesion;
    }

    public function setMontantAdhesion(int $montant_adhesion): static
    {
        $this->montant_adhesion = $montant_adhesion;

        return $this;
    }

    public function getGroupe(): ?groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, recette>
     */
    public function getRecette(): Collection
    {
        return $this->recette;
    }

    public function addRecette(recette $recette): static
    {
        if (!$this->recette->contains($recette)) {
            $this->recette->add($recette);
            $recette->setUserm($this);
        }

        return $this;
    }

    public function removeRecette(recette $recette): static
    {
        if ($this->recette->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getUserm() === $this) {
                $recette->setUserm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, pole>
     */
    public function getPole(): Collection
    {
        return $this->pole;
    }

    public function addPole(pole $pole): static
    {
        if (!$this->pole->contains($pole)) {
            $this->pole->add($pole);
        }

        return $this;
    }

    public function removePole(pole $pole): static
    {
        $this->pole->removeElement($pole);

        return $this;
    }

    /**
     * @return Collection<int, ressource>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(ressource $ressource): static
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setAuteurice($this);
        }

        return $this;
    }

    public function removeRessource(ressource $ressource): static
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getAuteurice() === $this) {
                $ressource->setAuteurice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, motivation>
     */
    public function getMotivation(): Collection
    {
        return $this->motivation;
    }

    public function addMotivation(motivation $motivation): static
    {
        if (!$this->motivation->contains($motivation)) {
            $this->motivation->add($motivation);
        }

        return $this;
    }

    public function removeMotivation(motivation $motivation): static
    {
        $this->motivation->removeElement($motivation);

        return $this;
    }
}