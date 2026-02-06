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

    public function getSaison(): Saison
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();

        // Si l’utilisateur change la saison via ?saison=ID
        if ($request->query->get('saison')) {
            $session->set('saisonCourante', $request->query->get('saison'));
        }

        // Saison choisie en session
        if ($session->has('saisonCourante')) {
            $saison = $this->saisonRepository->find($session->get('saisonCourante'));
            if ($saison) {
            return $saison;
        }
    }

    // Sinon : saison courante selon la date du jour
    $saison = $this->saisonRepository->findSaisonCourante();
    if ($saison) {
        $session->set('saisonCourante', $saison->getId());
        return $saison;
    }

    // Fallback : dernière saison créée
    $saison = $this->saisonRepository->findOneBy([], ['dateDebut' => 'DESC']);
    $session->set('saisonCourante', $saison->getId());

    return $saison;
}

}
