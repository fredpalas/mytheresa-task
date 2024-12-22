<?php

namespace App\Shop\Product\Domain;

use App\Shop\Product\Domain\Events\ProductCreatedDomainEvent;
use App\Shared\Domain\Aggregate\AggregateRoot;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Product extends AggregateRoot
{
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        private readonly ProductId $id,
        private readonly ProductSku $sku,
        private ProductName $name,
        private ProductCategory $category,
        private ProductPrice $price,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $updatedAt = null
    ) {
        $this->updatedAt = $updatedAt ?? new DateTime();
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
    }

    public static function create(
        ProductId $id,
        ProductSku $sku,
        ProductName $name,
        ProductCategory $category,
        ProductPrice $price,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $updatedAt = null
    ): self {
        $product = new self($id, $sku, $name, $category, $price, $createdAt, $updatedAt);

        $product->record(new ProductCreatedDomainEvent(
            $product->id()->value(),
            $product->sku()->value(),
            $product->name()->value(),
            $product->category()->value(),
            $product->price()->value(),
            $product->createdAt()->format(DateTime::ATOM),
            $product->updatedAt()->format(DateTime::ATOM)
        ));

        return $product;
    }

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function sku(): ProductSku
    {
        return $this->sku;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function category(): ProductCategory
    {
        return $this->category;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }
}
