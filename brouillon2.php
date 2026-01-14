public function __construct(
        private SaisonRepository $saisonRepository,
        private AdhesionRepository $adhesionRepository
    ) {}

    public function index(): Response
    {
        // Saison en cours par défaut (la plus récente)
        $saisonEnCours = $this->saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);

        // Compteur d’adhésions pour la saison sélectionnée
        $nbAdhesions = $saisonEnCours
            ? $this->adhesionRepository->count(['saison' => $saisonEnCours])
            : 0;

        return $this->render('admin/dashboard.html.twig', [
            'nbAdhesions' => $nbAdhesions,
        ]);
    }