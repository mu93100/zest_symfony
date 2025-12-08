<? php










#[ORM\Entity]
class Adhesion
{
    #[ORM\Id, ORM\GeneratedValue]
    private ?int $id = null;

}


    // --- Getters / Setters ---



    public function getMontantAdhesion(): ?MontantAdhesion { return $this->montantAdhesion; }
    public function setMontantAdhesion(?MontantAdhesion $montant): self { $this->montantAdhesion = $montant; return $this; }


    public function getMotivations(): Collection { return $this->motivations; }
    public function addMotivation(Motivation $m): self {
        if (!$this->motivations->contains($m)) { $this->motivations->add($m); }
        return $this;
    }
    public function removeMotivation(Motivation $m): self { $this->motivations->removeElement($m); return $this; }


    public function getParticipations(): Collection { return $this->participations; }
    public function addParticipation(ParticipationDispo $p): self {
        if (!$this->participations->contains($p)) { $this->participations->add($p); }
        return $this;
    }
    public function removeParticipation(ParticipationDispo $p): self { $this->participations->removeElement($p); return $this; }

    public function getPoles(): Collection { return $this->poles; }
    public function addPole(Pole $pole): self {
        if (!$this->poles->contains($pole)) { $this->poles->add($pole); }
        return $this;
    }
    public function removePole(Pole $pole): self { $this->poles->removeElement($pole); return $this; }



// ADHESION
// création saisons ADMIN

//     L’admin clique sur “Créer nouvelle saison”.

//     Une commande Symfony ou une action en back-office génère les nouvelles adhésions 
//     pour tous les utilisateurs existants, avec la nouvelle valeur saison.
// src/Command/CreateNewSeasonCommand.php
#[AsCommand(name: 'app:create-new-season')]
class CreateNewSeasonCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nouvelleSaison = '2026/2027';
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $adhesion = new Adhesion();
            $adhesion->setUser($user);
            $adhesion->setGroupe($user->getGroupe());
            $adhesion->setSaison($nouvelleSaison);
            $adhesion->setDateAdhesion(new \DateTime());

            $this->em->persist($adhesion);
        }

        $this->em->flush();
        $output->writeln("Nouvelle saison $nouvelleSaison créée !");
        return Command::SUCCESS;
    }
}
    Chaque adhésion est liée à un user, un groupe, et une saison.

    Tu peux ensuite filtrer facilement les adhésions par saison avec une requête Doctrine :

$adhesions = $adhesionRepository->findBy(['saison' => '2025/2026']);
    // $output->writeln("Nouvelle saison $nouvelleSaison créée !");
    // return Command::SUCCESS;