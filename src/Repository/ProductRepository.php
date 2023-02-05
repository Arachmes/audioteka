<?php

namespace App\Repository;

use App\Service\Catalog\Product;
use App\Service\Catalog\ProductProvider;
use App\Service\Catalog\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Ramsey\Uuid\Uuid;

class ProductRepository implements ProductProvider, ProductService
{
    public const PREFIX = 'p';
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(\App\Entity\Product::class);
    }

    public function getProducts(int $page = 0, int $count = 3, OrderBy $orderBy = null): iterable
    {
        $queryBuilder = $this->repository->createQueryBuilder(self::PREFIX)
            ->setMaxResults($count)
            ->setFirstResult($page * $count);

        if ($orderBy && $orderBy->count()) {
            $queryBuilder->orderBy($orderBy);
        }


        return $queryBuilder->getQuery()->getResult();
    }

    public function getTotalCount(): int
    {
        return $this->repository->createQueryBuilder('p')->select('count(p.id)')->getQuery()->getSingleScalarResult();
    }

    public function exists(string $productId): bool
    {
        return $this->repository->find($productId) !== null;
    }

    public function add(string $name, int $price): Product
    {
        $product = new \App\Entity\Product(Uuid::uuid4(), $name, $price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function remove(string $id): void
    {
        $product = $this->repository->find($id);
        if ($product !== null) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        }
    }

    public function getOrderColumn(string $columnName): ?string
    {

        $meta = $this->entityManager->getClassMetadata(\App\Entity\Product::class);

        if ($meta->hasField($columnName)) {
            return self::PREFIX . '.' . $columnName;
        }

        return null;
    }

}
