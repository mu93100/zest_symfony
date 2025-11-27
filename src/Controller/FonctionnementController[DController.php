<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FonctionnementController[DController extends AbstractController
{
    #[Route('/fonctionnement/controller/d', name: 'app_fonctionnement_controller_d')]
    public function index(): Response
    {
        return $this->render('fonctionnement_controller[d/index.html.twig', [
            'controller_name' => 'FonctionnementController[DController',
        ]);
    }
}
