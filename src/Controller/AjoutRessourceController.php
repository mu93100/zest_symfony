<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AjoutRessourceController extends AbstractController
{
    #[Route('/ajout/ressource', name: 'app_ajout_ressource')]
    public function index(): Response
    {
        return $this->render('ajout_ressource/index.html.twig', [
            'controller_name' => 'AjoutRessourceController',
        ]);
    }
}
