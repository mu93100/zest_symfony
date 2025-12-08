REPOSITORY
« intermédiaire » entre ton code et la base de données pour une entité donnée.
C’est une classe dédiée pour lire les données d’une table via l’entité associée : find(), findAll(), findBy(), findOneBy(), etc.​

Il permet de centraliser et réutiliser toutes les requêtes liées à une entité (UserRepository pour User, ArticleRepository pour Article, etc.), au lieu d’écrire du SQL partout dans tes contrôleurs/commandes.​

Pourquoi c’est utile
Tu peux y écrire des méthodes personnalisées comme findActiveUsers(), findByRole('ADMIN'), findLatestArticles(), etc., ce qui garde ton code plus clair et plus testable.​

Symfony/Doctrine te génère déjà un repository par entité, et tu l’injectes ensuite (UserRepository $userRepository) pour récupérer facilement les objets depuis la BDD.​

il a:
**public function __construct()**
 et autres fonctions
 public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ressource
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }