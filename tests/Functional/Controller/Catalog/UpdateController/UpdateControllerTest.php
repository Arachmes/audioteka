<?php

namespace App\Tests\Functional\Controller\Catalog\UpdateController;

use App\Tests\Functional\WebTestCase;

class UpdateControllerTest extends WebTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new UpdateControllerFixture());
    }

    public function test_update_product(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [
            'name' => 'Product updated',
            'price' => 2023,
        ]);

        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/products');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
        self::assertequals('Product updated', $response['products'][0]['name']);
        self::assertequals(2023, $response['products'][0]['price']);
    }

    public function test_update_product_only_name(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [
            'name' => 'Product updated',
        ]);

        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/products');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
        self::assertequals('Product updated', $response['products'][0]['name']);
        self::assertequals(1990, $response['products'][0]['price']);
    }

    public function test_update_product_only_price(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [
            'price' => 2023,
        ]);

        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/products');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
        self::assertequals('Product to update', $response['products'][0]['name']);
        self::assertequals(2023, $response['products'][0]['price']);
    }

    public function test_product_cannot_update_with_empty_name(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [
            'name' => '    ',
        ]);

        self::assertResponseStatusCodeSame(422);

        $response = $this->getJsonResponse();
        self::assertequals('Invalid name or price.', $response['error_message']);
    }


    public function test_product_cannot_update_with_non_positive_price(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [
            'price' => 0,
        ]);

        self::assertResponseStatusCodeSame(422);

        $response = $this->getJsonResponse();
        self::assertequals('Invalid name or price.', $response['error_message']);
    }
}
