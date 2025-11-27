<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AjoutRecetteController extends AbstractController
{
    #[Route('/ajout/recette', name: 'app_ajout_recette')]
    public function index(): Response
    {
        return $this->render('ajout_recette/index.html.twig', [
            'controller_name' => 'AjoutRecetteController',
        ]);
    }
}
