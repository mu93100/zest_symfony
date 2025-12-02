<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Pole;
use App\Entity\motivation;
use App\Entity\Groupe;
use App\Entity\Adhesion;
use App\Entity\ParticipationDispo;
use App\Entity\Ressource;
use App\Entity\Photos;
use App\Entity\Categorie;
use App\Entity\Producteurice;
use App\Entity\Produit;
use App\Entity\Recette;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('C O R T O - Z E S T admin')
            // ->setFaviconPath('favicon.ico')
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // 🔹 Utilisateurs & Groupes
        yield MenuItem::subMenu('Adhérents', 'fas fa-users')->setSubItems([
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
            MenuItem::linkToCrud('Motivations', 'fas fa-lightbulb', Motivation::class),
            MenuItem::linkToCrud('Disponibilités', 'fas fa-calendar-check', ParticipationDispo::class),
            MenuItem::linkToCrud('Adhésions', 'fas fa-id-card', Adhesion::class),
        ]);

        // 🔹 Organisation interne
        yield MenuItem::subMenu('Organisation', 'fas fa-sitemap')->setSubItems([
            MenuItem::linkToCrud('Pôles', 'fas fa-network-wired', Pole::class),
            MenuItem::linkToCrud('Groupes', 'fas fa-layer-group', Groupe::class),
        ]);

        // 🔹 Contenus & médias
        yield MenuItem::subMenu('Contenus', 'fas fa-photo-video')->setSubItems([
            MenuItem::linkToCrud('Photos', 'fas fa-image', Photos::class),
            MenuItem::linkToCrud('Recettes', 'fas fa-utensils', Recette::class),
            MenuItem::linkToCrud('Ressources', 'fas fa-book', Ressource::class),
        ]);

        // 🔹 Produits & producteurs
        yield MenuItem::subMenu('Produits', 'fas fa-shopping-basket')->setSubItems([
            MenuItem::linkToCrud('Catégories', 'fas fa-tags', Categorie::class),
            MenuItem::linkToCrud('Produits', 'fas fa-carrot', Produit::class),
            MenuItem::linkToCrud('Producteur·ices', 'fas fa-tractor', Producteurice::class),
        ]);
    }
}
// Organisation : si tu as beaucoup d’entités, tu peux regrouper avec :

// php
// yield MenuItem::subMenu('Gestion', 'fas fa-cogs')->setSubItems([
//     MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
//     MenuItem::linkToCrud('Pôles', 'fas fa-building', Pole::class),
// ]);