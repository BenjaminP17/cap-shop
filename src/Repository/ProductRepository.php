<?php

namespace App\Repository;

use App\Entity\Product;
use App\Enum\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /** 5 derniers drops, triés du plus récent au plus ancien */
    public function findRecentDrops(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isActive = true')
            ->orderBy('p.droppedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /** 4 bestsellers, triés par nombre de ventes */
    public function findBestsellers(int $limit = 4): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isActive = true')
            ->andWhere('p.isBestseller = true')
            ->orderBy('p.soldCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /** Produits par catégorie */
    public function findByCategory(Category $category, int $limit = 20): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isActive = true')
            ->andWhere('p.category = :cat')
            ->setParameter('cat', $category->value)
            ->orderBy('p.droppedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
