<?php

namespace App\Repository;

use App\Entity\CartProduct;
use App\Service\CartProduct\CartProductService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartProduct>
 *
 * @method CartProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartProduct[]    findAll()
 * @method CartProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartProductsRepository extends ServiceEntityRepository implements CartProductService
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }


    public function getOneByCartAndProduct(string $cartId, string $productId): ?CartProduct
    {
        return $this->findOneBy(['cart' => $cartId, 'product' => $productId]);
    }
}
