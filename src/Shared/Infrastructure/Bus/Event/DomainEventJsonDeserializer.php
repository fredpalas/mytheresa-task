<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Utils;

final readonly class DomainEventJsonDeserializer
{
    public function __construct(private DomainEventMap $mapping)
    {
    }

    public function deserialize(string $domainEvent): DomainEvent
    {
        $eventData = Utils::jsonDecode($domainEvent);
        $eventName = $eventData['event']['type'];

        $eventClass = $this->mapping->for($eventName);

        if (class_exists($eventClass) === false) {
            throw new DomainEventNotSerializableException("The event <$eventName> doesn't exist");
        }

        return $eventClass::fromPrimitives(
            $eventData['event']['attributes']['id'],
            $eventData['event']['attributes'],
            $eventData['event']['id'],
            $eventData['event']['occurred_on']
        );
    }
}
