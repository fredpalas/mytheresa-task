<?php

namespace App\Shop\Product\Domain\Events;

use App\Shared\Domain\Bus\Event\DomainEvent;

class ProductCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        private string $sku,
        private string $name,
        private string $category,
        private int $price,
        ?string $eventId = null,
        ?string $occurredOn = null
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            $body['sku'],
            $body['name'],
            $body['category'],
            $body['price'],
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'mytheresa.shop.product.product_created';
    }

    public function toPrimitives(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
        ];
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function price(): int
    {
        return $this->price;
    }
}
