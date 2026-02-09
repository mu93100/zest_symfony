<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\SaisonRepository;
use App\Entity\Saison;

class SaisonContext
{
    public function __construct(
        private RequestStack $requestStack,
        private SaisonRepository $saisonRepository
    ) {}

    public function getSaison(): ?Saison
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();

        // Si l’admin change la saison via ?saison=ID
        if ($request->query->get('saison')) {
            $session->set('saisonCourante', $request->query->get('saison'));
        }

        // Saison en session
        if ($session->has('saisonCourante')) {
            return $this->saisonRepository->find($session->get('saisonCourante'));
        }

        // Aucune saison choisie → on renvoie null
        return null;
    }

    public function getAll(): array
    {
        return $this->saisonRepository->findBy([], ['dateDebut' => 'DESC']);
    }
}
