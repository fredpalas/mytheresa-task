<?php

namespace App\Shared\Application\Finder;

use App\Shared\Domain\Criteria\Criteria;

interface FinderInterface
{
    public function __invoke(
        array $filter,
        string $orderBy,
        string $order,
        ?int $limit = null,
        ?int $offset = null
    ): array;

    public static function getCriteria(
        array $filter,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset
    ): Criteria;
}
