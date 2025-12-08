<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\AdhesionRepository;
use App\Entity\Adhesion;

//-------------commande pour creation nouvelle saison par admin
#[AsCommand(
    name: 'app:creation-nouvelle-saison',
    description: 'Créer les adhésions pour la nouvelle saison',
)]
class CreationNouvelleSaisonCommand extends Command
{ // pour créer les nouvelles adhésions : on met en argument de function __construct :
    // EntityManager (pour persister en base) ET UserRepository (pour récupérer tous les utilisateurs).
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        // private MailerInterface $mailer,
        // private Twig\Environment $twig
        )
    {
        parent::__construct();
    }

    // définition des arguments (valeur obligatoire pour nommer Nlle saison) 
    // et option (generer un envoi de mail) pour creation de Nlle saison
    protected function configure(): void
    {  
        $this
            ->addArgument('saison', InputArgument::OPTIONAL, 'la saison à créer_format AAAA/AAAA')
            ->addOption('envoyer-mail-adhesion', null, InputOption::VALUE_NONE, 'Envoyer un mail pour nouvelle adhésion')
        ;
    }
    // logique métier ->créer les nouvelles adhésions
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // 🔹 Argument pour passer la saison en paramètre
        $saison = $input->getArgument('saison') ?? '2026/2027';

        // 🔹 Vérifier si la saison existe déjà
        // $existing = $this->adhesionRepository->findOneBy(['saison' => $saison]);
        // if ($existing) {
        //     $io->warning("⚠️ La saison $saison existe déjà, aucune création de saison effectuée ⚠️ ");
        //     return Command::SUCCESS;
        // }

        // 🔹 Créer les nouvelles adhésions        
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

        $io->success("✅ N O U V E L L E   S A I S O N   $saison créée ✅");

        // 🔹 Option pour envoyer un mail (future logique)
        if ($input->getOption('envoyer-mail-adhesion')) {
            $io->note("📧 Envoi des mails d’adhésion activé (à implémenter).");
        }

        return Command::SUCCESS;
    }
 //SymfonyStyle ($io) sert à afficher des messages jolis dans le terminal (success, note, etc.)
}
