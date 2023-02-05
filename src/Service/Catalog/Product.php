<?php

namespace App\Service\Catalog;

interface Product
{
    public function getId(): string;
    public function getName(): string;
    public function getPrice(): int;
    public function getCreated(): ?\DateTimeInterface;
    public function setName(string $name): Product;
    public function setPrice(int $price): Product;
}
