<?php

namespace App\Twig;

use App\Repository\SaisonRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class SaisonExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private SaisonRepository $saisonRepository,
        private RequestStack $requestStack
    ) {}

    public function getGlobals(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $saisonId = $request?->query->get('saison');

        $saisonEnCours = $saisonId
            ? $this->saisonRepository->find($saisonId)
            : $this->saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);

        return [
            'saisons' => $this->saisonRepository->findAll(),
            'saisonEnCours' => $saisonEnCours,
        ];
    }
}

// + rajout dans config/services.yaml
// App\Twig\SaisonExtension:
//     arguments:
//         - '@App\Repository\SaisonRepository'
//     tags: ['twig.extension']