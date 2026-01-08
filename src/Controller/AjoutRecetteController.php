<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Media;
use App\Form\RecetteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


final class AjoutRecetteController extends AbstractController
{
    #[Route('/ajout/recette', name: 'app_ajout_recette')]
    public function index(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $recette = new Recette();
        $recette->setDatePublication(new \DateTimeImmutable());

        $form = $this->createForm(RecetteFormType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 🔥 Associer l’utilisateur connecté
            $recette->setAuteurice($this->getUser());

            // 🔥 Gestion de la photo
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {

                // Nom de fichier sécurisé
                $originalName = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = $slugger->slug($originalName);
                $newFilename = $safeName . '-' . uniqid() . '.' . $photoFile->guessExtension();

                // Déplacement du fichier
                $photoFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );

                // 🔥 Création du Media
                $media = new Media();
                $media->setNomFichier($newFilename);
                $media->setDescription("Photo de la recette : " . $recette->getTitre());
                $media->setType("image");
                $media->setRole("photo_principale");

                // 🔥 Lier le media à la recette
                $media->setRecette($recette);

                $em->persist($media);
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
