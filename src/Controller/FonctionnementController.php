<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FonctionnementController extends AbstractController
{
    #[Route('/fonctionnement/controller', name: 'app_fonctionnement_controller')]
    public function index(): Response
    {
        return $this->render('fonctionnement_controller.html.twig', [
            'controller_name' => 'FonctionnementController',
        ]);
    }
}
