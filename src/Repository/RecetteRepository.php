<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }
    
    public function findByProduitOrProducteurice(array $produitSlugs, ?string $producteuriceSlug, int $limit, int $offset): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.produit', 'p')
            ->leftJoin('p.producteurices', 'prod')
            ->addSelect('p', 'prod')
            ->orderBy('r.datePublication', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if (!empty($produitSlugs)) {
            $qb->andWhere('p.slug IN (:produits)')
                ->setParameter('produits', $produitSlugs);
        }

        if ($producteuriceSlug) {
            $qb->andWhere('prod.slug = :producteurice')
                ->setParameter('producteurice', $producteuriceSlug);
        }

        return $qb->getQuery()->getResult();
    }
}

