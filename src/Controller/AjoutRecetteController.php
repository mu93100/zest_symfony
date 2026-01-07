<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AjoutRecetteController extends AbstractController
{
    #[Route('/ajout/recette', name: 'app_ajout_recette')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $recette = new Recette();
        $recette->setDatePublication(new \DateTime());
        $recette->setAuteurice($this->getUser());

        $form = $this->createForm(RecetteFormType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // gestion de la photo (Media) si tu veux
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                // on fera l’upload ici
            }

            $em->persist($recette);
            $em->flush();

            return $this->redirectToRoute('app_recettes');
        }

        return $this->render('ajout_recette/index.html.twig', [
            'recetteForm' => $form->createView(),
        ]);
    }
}
