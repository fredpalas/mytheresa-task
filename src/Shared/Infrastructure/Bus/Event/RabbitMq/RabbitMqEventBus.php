<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPException;
use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Infrastructure\Bus\Event\DomainEventJsonSerializer;
use App\Shared\Infrastructure\Bus\Event\MySql\MySqlDoctrineEventBus;

use function Lambdish\Phunctional\each;

final class RabbitMqEventBus implements EventBus
{
    public function __construct(
        private readonly RabbitMqConnection $connection,
        private readonly string $exchangeName,
        private readonly MySqlDoctrineEventBus $failoverPublisher
    ) {
    }

    public function publish(DomainEvent ...$events): void
    {
        each($this->publisher(), $events);
    }

    private function publisher(): callable
    {
        return function (DomainEvent $event): void {
            try {
                $this->publishEvent($event);
            } catch (AMQPException) {
                $this->failoverPublisher->publish($event);
            }
        };
    }

    private function publishEvent(DomainEvent $event): void
    {
        $body       = DomainEventJsonSerializer::serialize($event);
        $routingKey = $event::eventName();
        $messageId  = $event->eventId();

        $this->connection->exchange($this->exchangeName)->publish(
            $body,
            $routingKey,
            AMQP_NOPARAM,
            [
                'message_id'       => $messageId,
                'content_type'     => 'application/json',
                'content_encoding' => 'utf-8',
            ]
        );
    }
}
