<?php

namespace App\Service\CartProduct;

use App\Entity\CartProduct;

interface CartProductService
{
    public function getOneByCartAndProduct(string $cartId, string $productId): ?CartProduct;
}