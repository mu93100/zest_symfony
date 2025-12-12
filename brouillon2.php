<?php
class DashboardController extends AbstractDashboardController
{
    private SaisonRepository $saisonRepository;
    private AdhesionRepository $adhesionRepository;

    public function __construct(SaisonRepository $saisonRepository, AdhesionRepository $adhesionRepository)
    {
        $this->saisonRepository = $saisonRepository;
        $this->adhesionRepository = $adhesionRepository;
    }

    public function index(Request $request): Response
    {
        $saisonId = $request->query->get('saison');
        $saisonEnCours = $saisonId
            ? $this->saisonRepository->find($saisonId)
            : $this->saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);

        $toutesSaisons = $this->saisonRepository->findAll();

        $nbAdhesions = $saisonEnCours
            ? $this->adhesionRepository->count(['saison' => $saisonEnCours])
            : 0;

        return $this->render('admin/dashboard.html.twig', [
            'saisonEnCours' => $saisonEnCours,
            'saisons' => $toutesSaisons,
            'nbAdhesions' => $nbAdhesions,
        ]);
    }
}
