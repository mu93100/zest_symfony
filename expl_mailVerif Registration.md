dans user.php
    #[ORM\Column]
    private bool $isVerified = false;
// pour indiquer si un compte utilisateur a été validé :
// ✅ true → l’utilisateur est vérifié (après avoir confirmé son email).
// ❌ false → l’utilisateur n’est pas encore vérifié.
Lors de l’inscription, isVerified reste false.

Quand l’utilisateur clique sur un lien de confirmation envoyé par email, ton code met à jour ce champ à true.

créer un fichier contrôleur de vérification : VerificationController.php
php bin/console make:controller VerificationController
il cree plusieurs fichiers MAIS ARRET FACUNDO
**src/templates/verification/index.html.twig**
{% extends 'base.html.twig' %}

{% block title %}Hello VerificationController!{% endblock %}

{% block body %}
<style>
    .example-wrapper { margin: 1em auto; max-width: 800px; width: 95%; font: 18px/1.5 sans-serif; }
    .example-wrapper code { background: #F5F5F5; padding: 2px 6px; }
</style>

<div class="example-wrapper">
    <h1>Hello {{ controller_name }}! ✅</h1>

    This friendly message is coming from:
    <ul>
        <li>Your controller at <code>C:/Users/EDW2425/Desktop/zest_symfony/src/Controller/VerificationController.php</code></li>
        <li>Your template at <code>C:/Users/EDW2425/Desktop/zest_symfony/templates/verification/index.html.twig</code></li>
    </ul>
</div>
{% endblock %}

**src/security/EmailVerifier.php**
<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string) $user->getId(),
            (string) $user->getEmail()
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, (string) $user->getId(), (string) $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

**src/controller/VerificationController.php**
<!-- <?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VerificationController extends AbstractController
{
    #[Route('/verify/{token}', name: 'app_verify')]
    public function verify(
        string $token,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response {
        // Cherche l'utilisateur avec ce token
        $user = $userRepository->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Token invalide ou expiré');
        }

        // Marque le compte comme vérifié
        $user->setIsVerified(true);
        $em->flush();

        $this->addFlash('success', 'Votre compte est maintenant vérifié !');

        return $this->redirectToRoute('app_login');
    }
} -->


**dans user.php**
crash de
//----------------v e r i f i c a t i o n   e m a i l   avec envoi de token pour validation en BDD
// pour indiquer si un compte utilisateur a été validé :
// ✅ true → l’utilisateur est vérifié (après avoir confirmé son email).
// ❌ false → l’utilisateur n’est pas encore vérifié.

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $verificationToken = null;


    // public function isVerified(): bool
    // {
    //     return $this->isVerified;
    // }

    // public function setIsVerified(bool $isVerified): static
    // {
    //     $this->isVerified = $isVerified;

    //     return $this;
    // }
    
    
    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(?string $token): self
    {
        $this->verificationToken = $token;
        return $this;
    }