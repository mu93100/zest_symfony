<?php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// class SecurityController extends AbstractController
// {
//     #[Route(path: '/login', name: 'app_login')]
//     public function login(AuthenticationUtils $authenticationUtils): Response
//     {
//         // get the login error if there is one
//         $error = $authenticationUtils->getLastAuthenticationError();

//         // last username entered by the user
//         $lastUsername = $authenticationUtils->getLastUsername();

//         return $this->render('security/login.html.twig', [
//             'last_username' => $lastUsername,
//             'error' => $error,
//         ]);
//     }

//     #[Route(path: '/logout', name: 'app_logout')]
//     public function logout(): void
//     {
//         throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
//     }
// } 


// modif pour ajout recette user non connecté/ au dessus = controller initial
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
{
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();
    
    // Redirection depuis paramètre GET
    $returnUrl = $request->query->get('return_url', 'recettes');
    $targetPath = $returnUrl === 'ajout/recette' ? '/ajout/recette' : '/recettes';

    return $this->render('security/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
        'target_path' => $targetPath,
    ]);
}

}
