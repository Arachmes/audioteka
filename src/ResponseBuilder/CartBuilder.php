<?php

namespace App\ResponseBuilder;

use App\Service\Cart\Cart;

class CartBuilder
{
    public function __invoke(Cart $cart): array
    {
        $data = [
            'total_price' => $cart->getTotalPrice(),
            'products' => []
        ];

        foreach ($cart->getCartProducts() as $cartProduct) {
            $data['products'][] = [
                'id' => $cartProduct->getProduct()->getId(),
                'name' => $cartProduct->getProduct()->getName(),
                'price' => $cartProduct->getProduct()->getPrice()
            ];
        }

        return $data;
    }
}
