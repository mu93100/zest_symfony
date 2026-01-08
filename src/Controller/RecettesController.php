<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;




final class RecettesController extends AbstractController
{
    #[Route('/recettes', name: 'app_recettes')]
    public function index(Request $request, RecetteRepository $recetteRepo): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 4;

        $recettes = $recetteRepo->findBy([], ['datePublication' => 'DESC'], $limit, ($page - 1) * $limit);

        $produit = $request->query->get('produit');
        $producteurice = $request->query->get('producteurice');

        $recettes = $recetteRepo->findByProduitOrProducteurice($produit, $producteurice, $limit, ($page - 1) * $limit);

        return $this->render('recettes/index.html.twig', [
            'recettes' => $recettes,
            'page' => $page,
        ]);
    }
}

