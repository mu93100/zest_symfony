<?php

namespace App\Controller;

use App\Repository\ProducteuriceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProducteuricesController extends AbstractController
{
    #[Route('/producteurices', name: 'app_producteurices')]
    public function index(ProducteuriceRepository $producteuriceRepository): Response
    {
        $producteurices = $producteuriceRepository->findAll();  // Récupère les données

        return $this->render('producteurices/index.html.twig', [
            'producteurices' => $producteurices,  //  PASSE AU TEMPLATE
        ]);
    }

}
