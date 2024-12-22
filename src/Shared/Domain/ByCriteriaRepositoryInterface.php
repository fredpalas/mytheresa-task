<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Criteria\Criteria;

interface ByCriteriaRepositoryInterface
{
    public function searchByCriteria(Criteria $criteria): array;
}
