<?php

namespace App\Service\Catalog;

interface ProductProvider
{
    /**
     * @return Product[]
     */
    public function getProducts(int $page = 0, int $count = 3): iterable;

    public function exists(string $productId): bool;

    public function getTotalCount(): int;

    public function getOrderColumn(string $columnName): ?string;
}
