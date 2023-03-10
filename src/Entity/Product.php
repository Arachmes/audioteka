<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Product implements \App\Service\Catalog\Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'integer', nullable: false)]
    private string $priceAmount;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false, columnDefinition: "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP")]
    private ?\DateTimeInterface $created = null;

    public function __construct(string $id, string $name, int $price, ?\DateTimeInterface $created = new \DateTime())
    {
        $this->id = Uuid::fromString($id);
        $this->name = $name;
        $this->priceAmount = $price;
        $this->created = $created;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->priceAmount;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }


    public function setName(string $name): \App\Service\Catalog\Product
    {
        $this->name = $name;

        return $this;
    }

    public function setPrice(int $price): \App\Service\Catalog\Product
    {
        $this->priceAmount = $price;

        return $this;
    }
}
