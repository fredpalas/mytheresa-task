<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use App\Shared\Domain\Utils;
use App\Tests\Shared\Infrastructure\Doctrine\MySqlDatabaseCleaner;
use Doctrine\ORM\EntityManager;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;

final class DatabaseConnections
{
    private readonly array $connections;

    public function __construct(iterable $connections)
    {
        $this->connections = Utils::iterableToArray($connections);
    }

    public function clear(): void
    {
        each(fn (EntityManager $entityManager) => $entityManager->clear(), $this->connections);
    }

    public function truncate(): void
    {
        apply(new MySqlDatabaseCleaner(), array_values($this->connections));
    }
}
