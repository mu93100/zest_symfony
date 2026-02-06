<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Pole;
use App\Entity\Motivation;
use App\Entity\Groupe;
use App\Entity\Adhesion;
use App\Entity\Dispo;
use App\Entity\Ressource;
use App\Entity\Categorie;
use App\Entity\Producteurice;
use App\Entity\Produit;
use App\Entity\Recette;
use App\Entity\Saison;
use App\Entity\Media;
use App\Entity\MontantAdhesion;
use App\Repository\SaisonRepository;
use App\Repository\AdhesionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Templates;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\SaisonContext;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('/styles/admin.css');
    }

    public function __construct(
        private SaisonContext $saisonContext,
        private AdhesionRepository $adhesionRepository,
    ) {}

    public function index(): Response
    {
        $saisonEnCours = $this->saisonContext->getSaison();
        $saisons = $this->saisonContext->getAll();

        $nbAdhesions = $this->adhesionRepository->count(['saison' => $saisonEnCours]);

        return $this->render('admin/dashboard.html.twig', [
            'saisons'       => $saisons,
            'saisonEnCours' => $saisonEnCours,
            'nbAdhesions'   => $nbAdhesions,
        ]);
    }

    public function configureTemplates(Templates $templates): Templates
    {
        // return $templates->addTemplate('layout', 'admin/layout.html.twig');
        return $templates
            ->addTemplate('layout', 'admin/easyadmin_layout.html.twig')
            ->addTemplate('field/produits', 'admin/fields/produits_flex_row.html.twig');
    }
    
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Créer une nouvelle saison', '', Saison::class);

        // Organisation dashboard avec menu et sous menu
        // Utilisateurs & Groupes
        yield MenuItem::subMenu('A D H E R E N T S')->setSubItems([
            MenuItem::linkToCrud('users', '', User::class),
            MenuItem::linkToCrud('groupes', '', Groupe::class),            
        ]);

        // Organisation interne
        yield MenuItem::subMenu('O R G A N I S A T I O N', '')->setSubItems([
            MenuItem::linkToCrud('pôles', '', Pole::class),
            MenuItem::linkToCrud('adhésions', '', Adhesion::class),
            MenuItem::linkToCrud('montant adhésions', '', MontantAdhesion::class),
            MenuItem::linkToCrud('motivations', '', Motivation::class),
            MenuItem::linkToCrud('disponibilités', '', Dispo::class),
        ]);

        // Contenus & médias
        yield MenuItem::subMenu('C O N T E N U S', '')->setSubItems([
            MenuItem::linkToCrud('recettes', '', Recette::class),
            MenuItem::linkToCrud('ressources', '', Ressource::class),
            MenuItem::linkToCrud('catégories', '', Categorie::class),
            MenuItem::linkToCrud('medias - photos/fichiers', '', Media::class),
        ]);

        // Produits & producteurs
        yield MenuItem::subMenu('P R O D U I T S', '')->setSubItems([
            MenuItem::linkToCrud('produits', '', Produit::class),
            MenuItem::linkToCrud('producteur·ices', '', Producteurice::class),
        ]);
    }
}