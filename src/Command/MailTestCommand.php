<?php
// ATTENTION AU DEPLOIEMENT SI SOUCI

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

#[AsCommand(
    name: 'app:mail-test',
    description: 'Envoie un email de test avec le template paiement_non_valide',
)]
class MailTestCommand extends Command
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 🔹 On simule un utilisateur et une saison
        $htmlContent = $this->twig->render('emails/paiement_non_valide.html.twig', [
            'user' => (object)['nom' => 'Muriel'],
            'saison' => '2026/2027',
        ]);

        $email = (new Email())
            ->from('admin@zest-site.fr')
            ->to('test@example.com') // mets ton adresse perso ou de test
            ->subject('Test du mail paiement non validé')
            ->html($htmlContent);

        $this->mailer->send($email);

        $output->writeln("✅ Email de test envoyé !");
        return Command::SUCCESS;
    }
}
