<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Fichier uploadé (non mappé)
    private ?File $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomFichier = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $role = null;

    #[ORM\ManyToOne(targetEntity: Producteurice::class, inversedBy: 'medias', cascade: ['persist'])]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Producteurice $producteurice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        if ($file instanceof UploadedFile) {
            $filename = uniqid() . '.' . $file->guessExtension();
            $file->move('uploads/medias', $filename);
            $this->nomFichier = $filename;
        }

        $this->file = $file;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(?string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getProducteurice(): ?Producteurice
    {
        return $this->producteurice;
    }

    public function setProducteurice(?Producteurice $producteurice): static
    {
        $this->producteurice = $producteurice;
        return $this;
    }
}
