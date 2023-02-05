<?php

namespace App\Messenger;

use App\Service\Catalog\ProductService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateProductToCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private ProductService $service) { }

    public function __invoke(UpdateProductToCatalog $command): void
    {
        $this->service->update($command->id, $command->name, $command->price);
    }
}