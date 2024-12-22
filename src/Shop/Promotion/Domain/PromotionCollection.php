<?php

namespace App\Shop\Promotion\Domain;

use App\Shared\Domain\Collection;

class PromotionCollection extends Collection
{
    protected function type(): string
    {
        return Promotion::class;
    }
}
