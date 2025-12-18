<?php

namespace App\Controller;

use App\Entity\Adhesion;
use App\Entity\Groupe;
use App\Entity\User;
use App\Form\AdhesionFormType;
use App\Repository\SaisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdhesionController extends AbstractController
{
    #[Route('/adhesion', name: 'app_adhesion')]
    public function index(Request $request, EntityManagerInterface $entityManager, SaisonRepository $saisonRepository): Response
    {
        $adhesion = new Adhesion();

        // Saison en cours
        $saisonEnCours = $saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);
        if ($saisonEnCours) {
            $adhesion->setSaison($saisonEnCours);
        }

        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Tu dois être connecté pour adhérer.');
        }

        $adhesionForm = $this->createForm(AdhesionFormType::class, $adhesion, [
            'user' => $user,
        ]);
        $adhesionForm->handleRequest($request);

        if ($adhesionForm->isSubmitted() && $adhesionForm->isValid()) {
            // --- Création d’un nouveau groupe ---
            $nouveauNom = $adhesionForm->get('nouveauGroupe')->getData();
            if ($nouveauNom) {
                $nouveauGroupe = new Groupe();
                $nouveauGroupe->setNom($nouveauNom);
                $nouveauGroupe->setAdresseDistrib($adhesionForm->get('adresseDistribution')->getData());
                $nouveauGroupe->setVille($adhesionForm->get('ville')->getData());
                $nouveauGroupe->setIsOpen((bool) $adhesionForm->get('isOpen')->getData());

                $entityManager->persist($nouveauGroupe);

                $adhesion->setGroupe($nouveauGroupe);
                $user->setGroupe($nouveauGroupe);

            // --- Changement de groupe existant ---
            } elseif ($adhesionForm->get('changeGroupe')->getData()) {
                $groupeChoisi = $adhesionForm->get('changeGroupe')->getData();
                $adhesion->setGroupe($groupeChoisi);
                $user->setGroupe($groupeChoisi);

            // --- Sinon garder le groupe actuel ---
            } else {
                $adhesion->setGroupe($user->getGroupe());
            }

            // --- Flag référent ---
            $user->setIsReferent((bool) $adhesionForm->get('isReferent')->getData());

            // --- Lier l’adhésion au user ---
            $adhesion->setUser($user);

            // --- Sauvegarde en base ---
            $entityManager->persist($adhesion);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Adhésion enregistrée ! Merci de régler par virement sous 8 jours.');
            return $this->redirectToRoute('app_adhesion');
        }

        return $this->render('adhesion/index.html.twig', [
            'adhesionForm' => $adhesionForm->createView(),
            'saison' => $saisonEnCours,
            'user' => $user,
        ]);
    }
}



{% block javascripts %}
	{{ parent() }}
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const groupeListe = document.getElementById('groupe-liste');
			const nouveauGroupeField = document.getElementById('nouveau-groupe-field');

			if (groupeListe && nouveauGroupeField) {
				// Masquer par défaut le champ nouveau groupe
				const formGroup = nouveauGroupeField.closest('.form-group') || nouveauGroupeField.parentElement;
				formGroup.style.display = 'none';

				groupeListe.addEventListener('change', function () {
					// Si "Nouveau groupe" est sélectionné (valeur vide du placeholder)
					if (groupeListe.value === '') {
						formGroup.style.display = 'block';
					} else {
						formGroup.style.display = 'none';
					}
				});
			}
		});
	</script>
{% endblock %}

MESSAGE APRES SUBMIT Erreur interne du serveur HTTP 500
["App\\Entity\\User","validateGroupChoice"] ciblé par la contrainte de rappel n'est pas une fonction appelable valide.

SI JE CHOISIS NOUVEAU GROUPE ET MET UN NOM DE NOUVEAU GROUPE / CA NE MARCHE PAS JE NE PEUX PAS M'ENREGISTRER



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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:creation-nouvelle-saison',
    description: 'Créer les adhésions pour la nouvelle saison',
)]
class CreationNouvelleSaisonCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private AdhesionRepository $adhesionRepository,
        private MailerInterface $mailer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('saison', InputArgument::OPTIONAL, 'La saison à créer (format AAAA/AAAA)')
            ->addOption('envoyer-mail-adhesion', null, InputOption::VALUE_NONE, 'Envoyer un mail pour chaque nouvelle adhésion');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // 🔹 Argument pour passer la saison en paramètre
        $saison = $input->getArgument('saison') ?? '2026/2027';

        // 🔹 Vérifier si la saison existe déjà
        $existing = $this->adhesionRepository->findOneBy(['saison' => $saison]);
        if ($existing) {
            $io->warning("⚠️ La saison $saison existe déjà, aucune création effectuée.");
            return Command::SUCCESS;
        }

        // 🔹 Créer les nouvelles adhésions
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $adhesion = new Adhesion();
            $adhesion->setUser($user);
            $adhesion->setGroupe($user->getGroupe());
            $adhesion->setSaison($saison);
            $adhesion->setDateAdhesion(new \DateTime());

            $this->em->persist($adhesion);

            // 🔹 Envoi de mail si option activée
            if ($input->getOption('envoyer-mail-adhesion')) {
                $email = (new Email())
                    ->from('admin@zest-site.fr')
                    ->to($user->getEmail())
                    ->subject("Nouvelle adhésion $saison")
                    ->text("Bonjour {$user->getNom()},\n\nVotre adhésion pour la saison $saison a été créée.\nMerci de votre participation !");
                
                $this->mailer->send($email);
            }            
        }

        $this->em->flush();

        $io->success("✅ Nouvelle saison $saison créée avec succès !");

        // 🔹 Option pour envoyer un mail (future logique)
        if ($input->getOption('envoyer-mail-adhesion')) {
            $io->note("✅ M A I L   D' A D H E S I O N   envoyé aux adhérent.e.s");
        }

        return Command::SUCCESS;
    }
}
