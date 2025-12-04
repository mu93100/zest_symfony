<?php

namespace App\Entity;

use App\Repository\MotivationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotivationRepository::class)]
class Motivation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'motivation')]
    private Collection $user_motiv;

    public function __construct()
    {
        $this->user_motiv = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
    

    /**
     * @return Collection<int, User>
     */
    public function getUserMotiv(): Collection
    {
        return $this->user_motiv;
    }

    public function addUserMotiv(User $userMotiv): static
    {
        if (!$this->user_motiv->contains($userMotiv)) {
            $this->user_motiv->add($userMotiv);
            $userMotiv->addMotivation($this);
        }

        return $this;
    }

    public function removeUserMotiv(User $userMotiv): static
    {
        if ($this->user_motiv->removeElement($userMotiv)) {
            $userMotiv->removeMotivation($this);
        }

        return $this;
    }
}
