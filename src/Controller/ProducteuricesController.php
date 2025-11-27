<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProducteuricesController extends AbstractController
{
    #[Route('/producteurices', name: 'app_producteurices')]
    public function index(): Response
    {
        return $this->render('producteurices/index.html.twig', [
            'controller_name' => 'ProducteuricesController',
        ]);
    }
}
