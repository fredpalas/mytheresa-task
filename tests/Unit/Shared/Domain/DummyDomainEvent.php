<?php

namespace App\Tests\Unit\Shared\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

class DummyDomainEvent extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        private readonly string $dummyProperty,
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
        return new self($aggregateId, $body['dummyProperty'], $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'cmrad.seminar.dummy.event';
    }

    public function toPrimitives(): array
    {
        return [
            'dummyProperty' => $this->dummyProperty,
        ];
    }

    public function dummyProperty(): string
    {
        return $this->dummyProperty;
    }
}
