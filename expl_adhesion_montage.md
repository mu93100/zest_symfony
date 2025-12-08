**création de saison**
// ADHESION
// création saisons ADMIN
//     L’admin clique sur “Créer nouvelle saison”.
//     Une commande Symfony ou une action en back-office génère les nouvelles adhésions 
//     pour tous les utilisateurs existants, avec la nouvelle valeur saison.

creation du fichier CreationNouvelleSaisonCommand.php
php bin/console make:command app:creation-nouvelle-saison
(src/Command/CreationNouvelleSaisonCommand.php)

-> pour créer les nouvelles adhésions : on met en argument de **function __construct()** :
    // EntityManager (pour persister en base) ET UserRepository (pour récupérer tous les utilisateurs).
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository
    )
    {
        parent::__construct();
    }
    + on rajoute en haut 
use Doctrine\ORM\EntityManagerInterface;.
use App\Repository\UserRepository;       

-> dans **function configure()** ON MODIFIE LES ARGUMENTS ET OPTIONS

-> ON CHANGE LA **function execute()** AVEC LA logique métier :créer les nouvelles adhésions

        $io = new SymfonyStyle($input, $output);

        // 🔹 Argument pour passer la saison en paramètre
        $saison = $input->getArgument('saison') ?? '2026/2027';

        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $adhesion = new Adhesion();
            $adhesion->setUser($user);
            $adhesion->setGroupe($user->getGroupe());
            $adhesion->setSaison($saison);
            $adhesion->setDateAdhesion(new \DateTime());

            $this->em->persist($adhesion);
        }

        $this->em->flush();

        $io->success("N O U V E L L E   S A I S O N   $saison créée ");
        return Command::SUCCESS;

**Tu récupères la saison passée en argument (ou tu mets une valeur par défaut).
Tu boucles sur tous les utilisateurs et tu crées une nouvelle Adhesion pour chacun.
Tu persistes et flush → toutes les nouvelles adhésions sont enregistrées en base.
Tu affiches un joli message avec SymfonyStyle.


**envoi d'un mail relance pour adhesion paiement non validé**
php bin/console make:listener AdhesionListener

 What event do you want to listen to?:
 > postPersist

 ça crée un fichier src/ EventListener/AdhesionListener.php (qu'il faut réécrire !)

 ds template >créer doss emails + un fichier twig adhesion_non_payee