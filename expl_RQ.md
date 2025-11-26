RESSOURCES/CATEGORIES
Pour récupérer toutes les ressources d’une catégorie (ex. “ressources1”) :

php
$categorie = $categorieRepository->find(1); // Catégorie 1
foreach ($categorie->getRessources() as $ressource) {
    echo $ressource->getTitre();
}
Pour récupérer la catégorie d’une ressource :

php
$ressource = $ressourceRepository->find(10);
echo $ressource->getCategorie()->getNom();

-------------------------------------
USERS/ADHESION
foreach ($montantAdhesion->getUsers() as $user) {
    echo $user->getNom();
}

-------------------------------------
