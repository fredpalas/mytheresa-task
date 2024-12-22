<?php

namespace App\Shop\Promotion\Infrastructure\Persistence\Doctrine;

use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;
use App\Shop\Promotion\Domain\PromotionId;

class PromotionIdType extends UuidType
{
    protected function typeClassName(): string
    {
        return PromotionId::class;
    }
}
