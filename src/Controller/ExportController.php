<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// pour export CSV (tableau/liste )
class ExportController extends AbstractController 
{ // ------------------------- export GROUPES
    // ----------------------- export tableau complet
    #[Route('/admin/export/groupes', name: 'export_groupes')] 
    public function exportGroupes(GroupeRepository $repo): Response
    {
        $groupes = $repo->findAll();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, [
            'ID',
            'Nom du groupe',
            'Adresse du groupe',
            'Membres - Nb',
            'Membres - nom',
            'Membres - tél.',
            'Membres - email',
            'Référents - nom',
            'Référents - tél.',
            'Référents - email'
        ]);

        foreach ($groupes as $groupe) {
            $referent = $groupe->getReferent();
            $refNom = $referent ? $referent->getPrenom().' '.$referent->getNom() : '';
            $refTel = $referent ? $referent->getTelephone() : '';
            $refEmail = $referent ? $referent->getEmail() : '';

            $membres = $groupe->getMembres();

            if (count($membres) > 0) {
                foreach ($membres as $membre) {
                    fputcsv($csv, [
                        $groupe->getId(),
                        $groupe->getNom(),
                        $groupe->getVille().', '. $groupe->getAdresseDistrib(),
                        $nbMembres = count($groupe->getMembres()),
                        $membre->getPrenom().' '.$membre->getNom(),
                        '="'.$membre->getTelephone().'"', // pour format telephone avec les 0 initiaux
                        $membre->getEmail(),
                        $refNom,
                        '="'.$refTel.'"',
                        $refEmail
                    ]);
                }
            }
        }

        rewind($csv);
        $content = stream_get_contents($csv);

        return new Response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="zest_structure_groupes.csv"',
        ]);
    }

    // ----------------------- export emails membres
    #[Route('/admin/export/mails-membres', name: 'export_mails_membres')]
    public function exportEmailsMembres(GroupeRepository $repo): Response
    {
        $groupes = $repo->findAll();
        $emails = [];

        foreach ($groupes as $groupe) {
            foreach ($groupe->getMembres() as $membre) {
                if ($membre->getEmail()) {
                    $emails[] = $membre->getEmail();
                }
            }
        }

        $content = implode(', ', $emails);

        return new Response($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="zest_emails_adherents.txt"',
        ]);
    }

    // ----------------------- export emails referents
    #[Route('/admin/export/mails-referents', name: 'export_mails_referents')]
    public function exportEmailsReferents(GroupeRepository $repo): Response
    {
        $groupes = $repo->findAll();
        $emails = [];

        foreach ($groupes as $groupe) {
            $ref = $groupe->getReferent();
            if ($ref && $ref->getEmail()) {
                $emails[] = $ref->getEmail();
            }
        }

        $content = implode(', ', $emails);

        return new Response($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="zest_emails_referents.txt"',
        ]);
    }
// ----------------------- export USERS
// ----------------------- export tableau complet
    #[Route('/admin/export/users', name: 'export_users')]
    public function exportUsers(UserRepository $repo): Response
    {
        $users = $repo->findAll();

        $csv = fopen('php://temp', 'r+');

        // En-têtes
        fputcsv($csv, [
            'ID',
            'Rôles',
            'Prénom',
            'Nom',
            'Email',
            'Téléphone',
            'Groupe',
            'Je suis référent',
            'Adresse',
            'Code postal',
            'Ville',
            'Date de naissance',
            'Nb enfants',
            'Compo foyer'       
        ]);

        foreach ($users as $user) {

            // Format téléphone avec zéro conservé
            $telephone = $user->getTelephone()
                ? '="'.$user->getTelephone().'"' : '';

            // Format date
            $dateNaissance = $user->getDateDeNaissance()
                ? $user->getDateDeNaissance()->format('d/m/Y') : '';

            // Rôles sous forme de texte
            $roles = implode(', ', $user->getRoles());

            // Groupe
            $groupe = $user->getGroupe()
                ? $user->getGroupe()->getNom() : '';

            // Groupe référent
            $groupeRef = $user->getGroupeReferent()
                ? $user->getGroupeReferent()->getNom() : '';

            fputcsv($csv, [
                $user->getId(),
                $roles,
                $user->getPrenom(),
                $user->getNom(),
                $user->getEmail(),
                $telephone,
                $groupe,
                $groupeRef,
                $user->getAdresse(),
                $user->getCodePostal(),
                $user->getVille(),
                $dateNaissance,
                $user->getNombreEnfants(),
                $user->getCompositionFoyer()
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);

        return new Response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="zest_adherents.csv"',
        ]);
    }

// ----------------------- export des emails des utilisateurs
    #[Route('/admin/export/users', name: 'export_mails_users')] 
    public function exportEmailUsers(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        $emails = [];

        foreach ($users as $user) {
            if ($user->getEmail()) {
                $emails[] = $user->getEmail();
            }
        }

        $content = implode(', ', $emails);

        return new Response($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="zest_emails_adherents.txt"',
        ]);
    }

// ----------------------- export des emails des referents
    #[Route('/admin/export/referents', name: 'export_mails_referents')]
    public function exportReferents(GroupeRepository $repo): Response
    {
        $groupes = $repo->findAll();
        $emails = [];

        foreach ($groupes as $groupe) {
            $ref = $groupe->getReferent();
            if ($ref && $ref->getEmail()) {
                $emails[] = $ref->getEmail();
            }
        }

        // supprimer les doublons
        $emails = array_unique($emails);

        $content = implode(', ', $emails);

        return new Response($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="zest_emails_referents.txt"',
        ]);
    }
}

