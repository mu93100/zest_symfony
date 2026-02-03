<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends AbstractController // pour export CSV (tableau/liste )
{
    #[Route('/admin/export/groupes', name: 'export_groupes')]
    public function export(GroupeRepository $repo): Response
    {
        $groupes = $repo->findAll();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, [
            'ID',
            'Nom du groupe',
            'Adresse du groupe',
            'Membres - NB',
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

    #[Route('/admin/export/mails-membres', name: 'export_mails_membres')]
    public function exportMailsMembres(GroupeRepository $repo): Response
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

    #[Route('/admin/export/mails-referents', name: 'export_mails_referents')]
    public function exportMailsReferents(GroupeRepository $repo): Response
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
}

