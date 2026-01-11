<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Request;

final class RecettesController extends AbstractController
{
    #[Route('/recettes', name: 'app_recettes')]
    public function index(Request $request, RecetteRepository $recetteRepo): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 3;

        // Récupération des filtres
        $produit = $request->query->get('produit');
        $producteurice = $request->query->get('producteurice');

        // Groupes de produits pour agrumes = plusieurs slugs
        $groupes = [
            'agrumes' => ['citron', 'orange', 'pamplemousse', 'kumquat', 'citron-bergamote', 'clementine-mandarine','cedrat'],
        ];

        // Détermination des slugs produits à filtrer
        if ($produit && isset($groupes[$produit])) {
            $produitSlugs = $groupes[$produit]; // tableau de slugs
        } else {
            $produitSlugs = $produit ? [$produit] : [];
        }

        // Si un filtre est appliqué → utiliser la méthode personnalisée
        if (!empty($produitSlugs) || $producteurice) {
            $recettes = $recetteRepo->findByProduitOrProducteurice(
                $produitSlugs,      
                $producteurice,
                $limit,
                ($page - 1) * $limit
            );
        } else {
            // Sinon → afficher toutes les recettes
            $recettes = $recetteRepo->findBy(
                [],
                ['datePublication' => 'DESC'],
                $limit,
                ($page - 1) * $limit
            );
        }


        return $this->render('recettes/index.html.twig', [
            'recettes' => $recettes,
            'page' => $page,
        ]);
    }
}
