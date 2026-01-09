<?php

namespace App\Entity;

use App\Entity\Producteurice;
use App\Entity\Media;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    //---------------- r e l a t i o n s  ManyToMany
    /**
     * @var Collection<int, Producteurice>
     */
    #[ORM\ManyToMany(targetEntity: Producteurice::class, mappedBy: 'produit')]
    private Collection $producteurices;

    //---------------- r e l a t i o n s  OneToMany
    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'produit')]
    private Collection $medias;




    public function __construct()
    {
        $this->producteurices = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Producteurice>
     */
    public function getProducteurices(): Collection
    {
        return $this->producteurices;
    }

    public function addProducteurice(Producteurice $producteurice): static
    {
        if (!$this->producteurices->contains($producteurice)) {
            $this->producteurices->add($producteurice);
            $producteurice->addProduit($this);
        }

        return $this;
    }

    public function removeProducteurice(Producteurice $producteurice): static
    {
        if ($this->producteurices->removeElement($producteurice)) {
            $producteurice->removeProduit($this);
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
            $media->setProduit($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): static
    {
        if ($this->medias->removeElement($media)) {
            if ($media->getProduit() === $this) {
                $media->setProduit(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function generateSlug(): void
    {
        $slugger = new AsciiSlugger();
        $this->slug = strtolower($slugger->slug($this->nom));
    }

}
