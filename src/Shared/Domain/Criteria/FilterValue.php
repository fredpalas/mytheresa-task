<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

final class FilterValue
{
    private mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function value(): mixed
    {
        return $this->value;
    }
}
