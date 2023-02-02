<?php

namespace App\Tests\Functional\Controller\Cart\AddProductController;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class AddProductControllerFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $products = [
            new Product('fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', 'Product 1', 1990),
            new Product('9670ea5b-d940-4593-a2ac-4589be784203', 'Product 2', 3990),
            new Product('15e4a636-ef98-445b-86df-46e1cc0e10b5', 'Product 3', 4990),
            new Product('00e91390-3af8-4735-bd06-0311e7131757', 'Product 4', 5990),
        ];

        foreach ($products as $product) {
            $manager->persist($product);
        }

        $cart = new Cart('5bd88887-7017-4c08-83de-8b5d9abde58c');
        $manager->persist($cart);

        $fullCart = new Cart('1e82de36-23f3-4ae7-ad5d-616295f1d6c0');
        $fullCart->addCartProduct(new CartProduct($fullCart, $products[0]));
        $fullCart->addCartProduct(new CartProduct($fullCart, $products[1]));
        $fullCart->addCartProduct(new CartProduct($fullCart, $products[2]));
        $manager->persist($fullCart);

        $cartForDuplicates = new Cart('354b2a95-a5a5-4cc6-8057-4619ddc8df41');
        $cartForDuplicates->addCartProduct(new CartProduct($cartForDuplicates, $products[0]));
        $manager->persist($cartForDuplicates);

        $fullCartWithDuplicates = new Cart('acf0a7ea-4c00-450e-bf89-f760ec566bac');
        $fullCartWithDuplicates->addCartProduct(new CartProduct($fullCart, $products[0]));
        $fullCartWithDuplicates->addCartProduct(new CartProduct($fullCart, $products[0]));
        $fullCartWithDuplicates->addCartProduct(new CartProduct($fullCart, $products[0]));
        $manager->persist($fullCartWithDuplicates);

        $manager->flush();
    }
}