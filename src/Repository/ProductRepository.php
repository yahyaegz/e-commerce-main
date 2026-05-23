<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
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

    /**
     * @return Product[]
     */
    public function findRecent(int $limit = 6): array
    {
        return $this->createQueryBuilder('product')
            ->leftJoin('product.category', 'category')
            ->addSelect('category')
            ->orderBy('product.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function findByCategory(Category $category): array
    {
        return $this->createQueryBuilder('product')
            ->leftJoin('product.category', 'category')
            ->addSelect('category')
            ->andWhere('product.category = :category')
            ->setParameter('category', $category)
            ->orderBy('product.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
