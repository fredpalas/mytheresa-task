<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\MySql;

use App\Shared\Domain\Utils;
use App\Shared\Infrastructure\Bus\Event\DomainEventMapping;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManager;
use RuntimeException;

use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

final class MySqlDoctrineDomainEventsConsumer
{
    private readonly Connection $connection;

    public function __construct(EntityManager $entityManager, private readonly DomainEventMapping $eventMapping)
    {
        $this->connection = $entityManager->getConnection();
    }

    public function consume(callable $subscribers, int $eventsToConsume): void
    {
        $events = $this->connection
            ->executeQuery("SELECT * FROM domain_events ORDER BY occurred_on ASC LIMIT $eventsToConsume")
            ->fetchAll(FetchMode::ASSOCIATIVE);

        each($this->executeSubscribers($subscribers), $events);

        $ids = implode(', ', map($this->idExtractor(), $events));

        if (!empty($ids)) {
            $this->connection->executeUpdate("DELETE FROM domain_events WHERE id IN ($ids)");
        }
    }

    private function executeSubscribers(callable $subscribers): callable
    {
        return function (array $rawEvent) use ($subscribers): void {
            try {
                $domainEventClass = $this->eventMapping->for($rawEvent['name']);
                $domainEvent      = $domainEventClass::fromPrimitives(
                    $rawEvent['aggregate_id'],
                    Utils::jsonDecode($rawEvent['body']),
                    $rawEvent['id'],
                    $this->formatDate($rawEvent['occurred_on'])
                );

                $subscribers($domainEvent);
            } catch (RuntimeException) {
            }
        };
    }

    private function formatDate($stringDate): string
    {
        return Utils::dateToString(new DateTimeImmutable($stringDate));
    }

    private function idExtractor(): callable
    {
        return static fn (array $event): string => sprintf("'%s'", $event['id']);
    }
}
