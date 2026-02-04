<?php

namespace App\EventListener;

use App\Entity\Adhesion;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class AdhesionListener
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    // postPersist → envoie un mail à la création de l’adhésion (paiementValide = false)
    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->handleAdhesion($args);
    }

    // postUpdate → envoie un mail à chaque modification, même pour changement de groupe ou saison
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->handleAdhesion($args);
    }

    private function handleAdhesion(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Adhesion) {
            return;
        }

        // 🔹 Si paiement = false → envoi du mail
        if (!$entity->isPaiementValide()) {
            $htmlContent = $this->twig->render('emails/paiement_non_valide.html.twig', [
                'user' => $entity->getUser(),
                'saison' => $entity->getSaison()->getNom(),
            ]);

            $email = (new Email())
                ->from('murielehlinger@gmail.com') // adhesion@corto-zest.org
                ->to($entity->getUser()->getEmail())
                ->subject("Paiement non validé pour la saison {$entity->getSaison()->getNom()}")
                ->html($htmlContent);

            $this->mailer->send($email);
        }
    }
}
