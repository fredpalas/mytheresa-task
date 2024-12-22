<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\OrderBy;
use App\Tests\Shared\Domain\WordMother;

final class OrderByMother
{
    public static function create(?string $fieldName = null): OrderBy
    {
        return new OrderBy($fieldName ?? WordMother::create());
    }
}
