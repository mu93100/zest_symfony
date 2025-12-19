dans base :         <div
                        class="alert alert-{{ type }} flash-message alert-dismissible fade show" role="alert">
                        {# Affiche le message flash (texte passé dans addFlash()) #}
                        {{ message }}
 
                        {# Bouton pour fermer l'alerte, Bootstrap 5 : btn-close + data-bs-dismiss #}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    exemple dans controller : // pour chaque ligne du panier
        foreach ($panierLignes as $lignePanier) {
            // $produit = produit de la ligne
            // $lignePanier->getProduit() -> produit de la ligne
            $produit = $lignePanier->getProduit();
            // $quantite = quantite de la ligne
            $quantite = $lignePanier->getQuantite();
 
            // si la quantite est superieur au stock
            if ($quantite > $produit->getStock()) {
                $this->addFlash('error', 'Le stock du produit "' . $produit->getNom() . '" est insuffisant.');
                return $this->redirectToRoute('panier_index');
            }
 
            $produit->setStock($produit->getStock() - $quantite);
 
            // $ligneCommande = nouvelle ligne de commande cretaion objet de entité ligne de commande
 
            $ligneCommande = new LigneCommande();
            // $ligneCommande->setCommande($commande) -> lier la ligne de commande à la commande
            $ligneCommande->setCommande($commande)
            // $ligneCommande->setProduit($produit) -> lier la ligne de commande au produit
                          ->setProduit($produit)
                          ->setQuantite($quantite)
                          ->setPrix($produit->getPrix());
 
 
// persist() prépare Doctrine à gérer un nouvel objet (ex : un nouveau produit qui n’existe pas encore en base).
            $em->persist($ligneCommande);
            // remove() supprime l'objet de la base de données
            $em->remove($lignePanier);
        }
 
        $em->persist($commande);
        $em->flush();
 
        $this->addFlash('success', 'Commande validée avec succès !');
        return $this->redirectToRoute('commande_index');
    }
 
    // Modifie la quantité d'un produit dans le panier user connecté
    #[Route('/panier/modifier/{id}', name: 'panier_modifier_quantite', methods: ['POST'])]
    public function modifierQuantite(Panier $ligne, Request $request, EntityManagerInterface $em): Response
    {
 
        
        if ($this->getUser()) {
            $quantite = max(1, (int) $request->request->get('quantite'));
 
            $ligne->setQuantite($quantite);
            $em->flush();
        }
 
        $this->addFlash('success', 'Quantité mise à jour.');
        return $this->redirectToRoute('panier_index');
    }
 
    // Modifie la quantité d'un produit dans le panier session pour user non connecté
    #[Route('/panier/modifier/session/{id}', name: 'panier_session_modifier_quantite', methods: ['POST'])]
    public function modifierQuantiteSession(int $id, Request $request, SessionInterface $session): Response
    {
        // on recupere le panier dans la session
        $panier = $session->get('paniers', []);
        // on ajoute max 1 et converti en int avec (int)
        // on ajoute la quantite dans le panier dans la session recupéré par la requete Post du form avec la clé quantite
        $quantite = max(1, (int) $request->request->get('quantite'));
      
 
        // si le produit est dans le panier
        // isset() vérifie si une variable est définie et n'est pas null
        // $panier[$id] est la ligne du panier correspondant au produit avec l'ID donné
        // $quantite est la nouvelle quantité
        if (isset($panier[$id])) {
            $panier[$id]['quantite'] = $quantite;
            $session->set('paniers', $panier);
        }
 
        $this->addFlash('success', 'Quantité mise à jour.');
        return $this->redirectToRoute('panier_index');
 