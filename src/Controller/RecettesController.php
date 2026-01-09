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

        // Si un filtre est appliqué → utiliser la méthode personnalisée
        if ($produit || $producteurice) {
            $recettes = $recetteRepo->findByProduitOrProducteurice(
                $produit,
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
