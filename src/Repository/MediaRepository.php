<?php

namespace App\Repository;

use App\Entity\Media;
use App\Entity\Recette;
use App\Entity\Ressource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Media>
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    //---------------- f i n d e r s  p o u r  R e c e t t e

    /**
     * Retourne la photo principale d'une recette
     */
    public function findPhotoPrincipaleRecette(Recette $recette): ?Media
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.recette = :recette')
            ->andWhere('m.role = :role')
            ->setParameter('recette', $recette)
            ->setParameter('role', 'photo_principale')
            ->getQuery()
            ->getOneOrNullResult();
    }

    //---------------- f i n d e r s  p o u r  R e s s o u r c e

    /**
     * Retourne la photo principale d'une ressource
     */
    public function findPhotoPrincipaleRessource(Ressource $ressource): ?Media
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.ressource = :ressource')
            ->andWhere('m.role = :role')
            ->setParameter('ressource', $ressource)
            ->setParameter('role', 'photo_principale')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne les photos supplémentaires d'une ressource
     */
    public function findPhotosSupplementaires(Ressource $ressource): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.ressource = :ressource')
            ->andWhere('m.role = :role')
            ->setParameter('ressource', $ressource)
            ->setParameter('role', 'photo_supplementaire')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les fichiers (pdf, doc...) d'une ressource
     */
    public function findFichiersRessource(Ressource $ressource): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.ressource = :ressource')
            ->andWhere('m.role = :role')
            ->setParameter('ressource', $ressource)
            ->setParameter('role', 'fichier')
            ->getQuery()
            ->getResult();
    }
}
