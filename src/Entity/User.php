<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /** * @var list<string> The user roles */
    #[ORM\Column]
    private array $roles = [];

    /** * @var string The hashed password */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 45)]
    private ?string $nom = null;

    #[ORM\Column(length: 45)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?bool $is_admin = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
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

    // #[ORM\Column]
    // private ?int $participation_dispo = null; 
    // PAS FK

    #[ORM\Column(type: Types::TEXT)]
    private ?string $competences = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?adhesion $adhesion = null;

    #[ORM\ManyToOne(inversedBy: 'membres')]
    private ?groupe $groupe = null;

    /**
     * @var Collection<int, recette>
     */
    #[ORM\OneToMany(targetEntity: recette::class, mappedBy: 'auteurice')]
    private Collection $recette;

    /**
     * @var Collection<int, pole>
     */
    #[ORM\ManyToMany(targetEntity: pole::class, inversedBy: 'users')]
    private Collection $pole;

    /**
     * @var Collection<int, ressource>
     */
    #[ORM\OneToMany(targetEntity: ressource::class, mappedBy: 'auteurice')]
    private Collection $ressource;

    /**
     * @var Collection<int, motivation>
     */
    #[ORM\ManyToMany(targetEntity: motivation::class, inversedBy: 'user_motiv')]
    private Collection $motivation;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?participationDispo $participationDispo = null;

    public function __construct()
    {
        $this->recette = new ArrayCollection();
        $this->pole = new ArrayCollection();
        $this->ressource = new ArrayCollection();
        $this->motivation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
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

    public function setAdresse(?string $adresse): static
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

    // public function getParticipationDispo(): ?int
    // {
    //     return $this->participation_dispo;
    // }

    // public function setParticipationDispo(int $participation_dispo): static
    // {
    //     $this->participation_dispo = $participation_dispo;

    //     return $this;
    // }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(string $competences): static
    {
        $this->competences = $competences;

        return $this;
    }

    public function getAdhesion(): ?adhesion
    {
        return $this->adhesion;
    }

    public function setAdhesion(?adhesion $adhesion): static
    {
        $this->adhesion = $adhesion;

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
            $recette->setAuteurice($this);
        }

        return $this;
    }

    public function removeRecette(recette $recette): static
    {
        if ($this->recette->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getAuteurice() === $this) {
                $recette->setAuteurice(null);
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
    public function getRessource(): Collection
    {
        return $this->ressource;
    }

    public function addRessource(ressource $ressource): static
    {
        if (!$this->ressource->contains($ressource)) {
            $this->ressource->add($ressource);
            $ressource->setAuteurice($this);
        }

        return $this;
    }

    public function removeRessource(ressource $ressource): static
    {
        if ($this->ressource->removeElement($ressource)) {
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

    public function getParticipationDispo(): ?participationDispo
    {
        return $this->participationDispo;
    }

    public function setParticipationDispo(?participationDispo $participationDispo): static
    {
        $this->participationDispo = $participationDispo;

        return $this;
    }
}
