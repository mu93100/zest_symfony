<?php

namespace App\Controller;

use App\Repository\RessourceRepository;
use App\Repository\ProduitRepository;
use App\Repository\ProducteuriceRepository;
use App\Repository\RecetteRepository;
use App\Repository\PoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(
        Request $request,
        ProduitRepository $produitRepo,
        ProducteuriceRepository $producteurRepo,
        RecetteRepository $recetteRepo,
        PoleRepository $poleRepo,
        RessourceRepository $ressourceRepo    
    ): Response {
        $q = trim($request->query->get('q', ''));
        $results = [];

        if ($q) {
            $results['ressources'] = $ressourceRepo->createQueryBuilder('a')
                ->where('a.titre LIKE :q OR a.sousTitre LIKE :q OR a.ressourceTexte LIKE :q')
                ->setParameter('q', '%' . $q . '%')
                ->orderBy('a.datePublication', 'DESC')
                ->getQuery()->getResult();

            $results['produits'] = $produitRepo->createQueryBuilder('a')
                ->where('a.nom LIKE :q OR a.description LIKE :q')
                ->setParameter('q', '%' . $q . '%')
                ->orderBy('a.nom', 'ASC')
                ->getQuery()->getResult();      

            $results['producteurices'] = $producteurRepo->createQueryBuilder('a')
                ->where('a.nom LIKE :q OR a.description LIKE :q')
                ->setParameter('q', '%' . $q . '%')
                ->orderBy('a.nom', 'ASC')
                ->getQuery()->getResult();

            $results['recettes'] = $recetteRepo->createQueryBuilder('a')
                ->where('a.titre LIKE :q OR a.ingredients LIKE :q OR a.description LIKE :q')
                ->setParameter('q', '%' . $q . '%')
                ->orderBy('a.datePublication', 'DESC')
                ->getQuery()->getResult();  

            $results['poles'] = $poleRepo->createQueryBuilder('a')
                ->where('a.nom LIKE :q OR a.descriptif LIKE :q')
                ->setParameter('q', '%' . $q . '%')
                ->orderBy('a.nom', 'ASC')
                ->getQuery()->getResult();  
        }

        return $this->render('search/index.html.twig', [
            'query' => $q,
            'results' => $results,
            'isSearchPage' => true
        ]);
    }
}
