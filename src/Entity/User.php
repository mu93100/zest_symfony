<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: '[! email déjà utilisé !]')]

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

//----------------p a s s w o r d
    /** * @var string The hashed password */
    #[ORM\Column]
    private ?string $password = null;
    #[Assert\NotBlank(message: '[M E R C I  de renseigner ton mot de passe]')]
    #[Assert\Length(min: 6, minMessage: '[M I N I M U M  {{ limit }} caractères]')]
    private ?string $plainPassword = null;
// plainPassword est un champ temporaire non mappé : sert uniquement à la saisie en clair dans les formulaires

    #[ORM\Column(length: 45)]
    private ?string $nom = null;

    #[ORM\Column(length: 45)]
    private ?string $prenom = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{10}$/', message: '[Téléphone : exactement 10 chiffres]')]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{5}$/', message: '[Code postal : exactement 5 chiffres]')]
    private ?string $codePostal = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDeNaissance = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $compositionFoyer = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $nombreEnfants = null;

    #[ORM\Column]
    private ?bool $isReferent = false;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $competences = null;


    //----------------r e l a t i o n s  ManyToOne
    #[ORM\ManyToOne(inversedBy: 'membres')]
    private ?Groupe $groupe = null;
    
//----------------r e l a t i o n s  ManyToMany
    /**
     * @var Collection<int, Pole>
     */
    #[ORM\ManyToMany(targetEntity: Pole::class, inversedBy: 'users')]
    private Collection $pole;

//----------------r e l a t i o n s  OneToMany

    #[ORM\OneToMany(targetEntity: Ressource::class, mappedBy: 'user')]
    private Collection $ressource;

    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'auteurice')]
    private Collection $recette;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Adhesion::class, cascade: ['persist', 'remove'])]
    private Collection $adhesions;



    //----------------f u n c t i o n s
    public function __construct()
    {
        $this->recette = new ArrayCollection();
        $this->pole = new ArrayCollection();
        $this->ressource = new ArrayCollection();
        $this->adhesions = new ArrayCollection();
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
//----------------p a s s w o r d
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
    // sérialisation CRC32C : pour remplacer le vrai hash du mot de passe par une empreinte courte dans la session 
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
        // Si tu utilises un champ temporaire plainPassword, on l'efface ici
        $this->plainPassword = null;
    }
//----------------fin  p a s s w o r d

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

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
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(?\DateTime $dateDeNaissance): static
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    public function getCompositionFoyer(): ?int 
    { 
        return $this->compositionFoyer; 
    }

    public function setCompositionFoyer(?int $v): self 
    {
        $this->compositionFoyer = $v; 
        
        return $this;
    }

    public function getNombreEnfants(): ?int
    {
        return $this->nombreEnfants;
    }

    public function setNombreEnfants(?int $nombreEnfants): static
    {
        $this->nombreEnfants = $nombreEnfants;

        return $this;
    }

    public function isReferent(): ?bool
    {
        return $this->isReferent;
    }

    public function setIsReferent(bool $isReferent): self
    {
        $this->isReferent = $isReferent;

        return $this;
    }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(string $competences): static
    {
        $this->competences = $competences;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, Pole>
     */
    public function getPole(): Collection
    {
        return $this->pole;
    }

    public function addPole(Pole $pole): static
    {
        if (!$this->pole->contains($pole)) {
            $this->pole->add($pole);
        }

        return $this;
    }

    public function removePole(Pole $pole): static
    {
        $this->pole->removeElement($pole);

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
            $adhesion->setUser($this);
        }
        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): self
    {
        if ($this->adhesions->removeElement($adhesion)) {
            if ($adhesion->getUser() === $this) {
                $adhesion->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessource(): Collection
    {
        return $this->ressource;
    }

    public function addRessource(Ressource $ressource): static
    {
        if (!$this->ressource->contains($ressource)) {
            $this->ressource->add($ressource);
            $ressource->setUser($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): static
    {
        if ($this->ressource->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getUser() === $this) {
                $ressource->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecette(): Collection
    {
        return $this->recette;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recette->contains($recette)) {
            $this->recette->add($recette);
            $recette->setAuteurice($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recette->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getAuteurice() === $this) {
                $recette->setAuteurice(null);
            }
        }

        return $this;
    }
}
