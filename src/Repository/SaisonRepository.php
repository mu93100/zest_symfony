<?php

namespace App\Repository;

use App\Entity\Saison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Saison>
 */
class SaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Saison::class);
    }

    public function findSaisonParDate(\DateTimeInterface $date): ?Saison
    {
        return $this->createQueryBuilder('s')
            ->where('s.dateDebut <= :date')
            ->andWhere('s.dateFin >= :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findSaisonCourante(): ?Saison
{
    return $this->findSaisonParDate(new \DateTime());
}

}
