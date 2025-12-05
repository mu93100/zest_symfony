<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Pole;
use App\Entity\Motivation;
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
        // symfony ::: return parent::index();
        // IA ::: Redirige vers une entité par défaut (ex: User)
        return $this->redirectToRoute('admin_user_index');
        
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

        // Organisation dashboard si beaucoup d’entités -> avec menu et sous menu
        // Utilisateurs & Groupes
        yield MenuItem::subMenu('A D H E R E N T S')->setSubItems([
            MenuItem::linkToCrud('Users', '', User::class),
            MenuItem::linkToCrud('Motivations', '', Motivation::class),
            MenuItem::linkToCrud('Disponibilités', '', ParticipationDispo::class),
            MenuItem::linkToCrud('Adhésions', '', Adhesion::class),
        ]);

        // Organisation interne
        yield MenuItem::subMenu('O R G A N I S A T I O N', '')->setSubItems([
            MenuItem::linkToCrud('Pôles', '', Pole::class),
            MenuItem::linkToCrud('Groupes', '', Groupe::class),
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
}
