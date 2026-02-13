<?php

namespace App\Service;

use App\Repository\SaisonRepository;
use App\Entity\Saison;

class SaisonContext
{
    private ?Saison $saison = null;

    public function __construct(
        private SaisonRepository $saisonRepository
    ) {}

    public function getSaison(): ?Saison
    {
        // Si déjà définie → OK
        if ($this->saison) {
            return $this->saison;
        }

        // Sinon → dernière saison en base
        $last = $this->saisonRepository->findOneBy([], ['dateDebut' => 'DESC']);

        if ($last) {
            $this->saison = $last;
        }

        return $this->saison;
    }

    public function setSaison(?Saison $saison): void
    {
        $this->saison = $saison;
    }

    public function getAll(): array
    {
        return $this->saisonRepository->findBy([], ['dateDebut' => 'DESC']);
    }
}
