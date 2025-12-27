<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Pole;
use App\Entity\Motivation;
use App\Entity\Groupe;
use App\Entity\Adhesion;
use App\Entity\Dispo;
use App\Entity\Ressource;
use App\Entity\Photos;
use App\Entity\Categorie;
use App\Entity\Producteurice;
use App\Entity\Produit;
use App\Entity\Recette;
use App\Entity\Saison;
use App\Repository\SaisonRepository;
use App\Repository\AdhesionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Templates;



#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
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
//
    public function configureTemplates(Templates $templates): Templates
    {
        return $templates->addTemplate('layout', 'admin/layout.html.twig');
    }
    
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Créer une nouvelle saison', '', Saison::class);


        // Organisation dashboard si beaucoup d’entités -> avec menu et sous menu
        // Utilisateurs & Groupes
        yield MenuItem::subMenu('A D H E R E N T S')->setSubItems([
            MenuItem::linkToCrud('Adhérents', '', User::class),
            MenuItem::linkToCrud('Groupes', '', Groupe::class),            
        ]);

        // Organisation interne
        yield MenuItem::subMenu('O R G A N I S A T I O N', '')->setSubItems([
            MenuItem::linkToCrud('Pôles', '', Pole::class),
            MenuItem::linkToCrud('Adhésions', '', Adhesion::class),
            MenuItem::linkToCrud('Motivations', '', Motivation::class),
            MenuItem::linkToCrud('Disponibilités', '', Dispo::class),
        ]);

        // Contenus & médias
        yield MenuItem::subMenu('C O N T E N U S', '')->setSubItems([
            MenuItem::linkToCrud('Recettes', '', Recette::class),
            MenuItem::linkToCrud('Ressources', '', Ressource::class),
            MenuItem::linkToCrud('Catégories', '', Categorie::class),
            MenuItem::linkToCrud('Photos', '', Photos::class),
        ]);

        // Produits & producteurs
        yield MenuItem::subMenu('P R O D U I T S', '')->setSubItems([
            MenuItem::linkToCrud('Produits', '', Produit::class),
            MenuItem::linkToCrud('Producteur·ices', '', Producteurice::class),
        ]);
    }

    // à rajouter : compteur d’adhésions par montant
    // use App\Entity\MontantAdhesion;
    // use Doctrine\ORM\EntityManagerInterface;

    // public function index(EntityManagerInterface $em): Response
    // {
    //     $data = $em->createQueryBuilder()
    //         ->select('m.libelle, COUNT(a.id) AS nbAdhesions')
    //         ->from(MontantAdhesion::class, 'm')
    //         ->leftJoin('m.adhesions', 'a')
    //         ->groupBy('m.id')
    //         ->getQuery()
    //         ->getResult();

    //     return $this->render('admin/dashboard.html.twig', [
    //         'stats' => $data,
    //     ]);
    // }
}


// CORRECTION
// public function index(
//     Request $request,
//     SaisonRepository $saisonRepository,
//     AdhesionRepository $adhesionRepository
// ): Response
// {
//     // 1) Choix de la saison: paramètre GET 'saison' ou saison en cours par défaut
//     $saisonId = $request->query->get('saison');
//     $saisonEnCours = $saisonId
//         ? $saisonRepository->find($saisonId)
//         : $saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);

//     // 2) Liste des saisons pour le sélecteur
//     $toutesSaisons = $saisonRepository->findAll();

//     // 3) Compteur d’adhésions pour la saison sélectionnée
//     $nbAdhesions = $saisonEnCours
//         ? $adhesionRepository->count(['saison' => $saisonEnCours])
//         : 0;

//     // 4) Rendu du dashboard personnalisé
//     return $this->render('admin/dashboard.html.twig', [
//         'saisonEnCours' => $saisonEnCours,
//         'saisons' => $toutesSaisons,
//         'nbAdhesions' => $nbAdhesions,
    