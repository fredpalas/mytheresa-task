<?php

namespace App\Shared\Application\Criteria;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filters;
use App\Shared\Domain\Criteria\Order;

trait CriteriaTrait
{
    public static function getCriteria(
        array $filter,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset
    ): Criteria {
        $filters = Filters::fromValues($filter);
        $order = Order::fromValues($orderBy, $order);

        return new Criteria($filters, $order, $offset, $limit);
    }
}
