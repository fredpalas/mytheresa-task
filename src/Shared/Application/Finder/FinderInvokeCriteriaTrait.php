<?php

namespace App\Shared\Application\Finder;

trait FinderInvokeCriteriaTrait
{
    public function __invoke(
        array $filter,
        string $orderBy,
        string $order,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $criteria = $this->getCriteria($filter, $orderBy, $order, $limit, $offset);

        return $this->repository->searchByCriteria($criteria);
    }
}
