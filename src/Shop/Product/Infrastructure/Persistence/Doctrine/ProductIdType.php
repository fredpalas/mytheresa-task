<?php

namespace App\Shop\Product\Infrastructure\Persistence\Doctrine;

use App\Shop\Product\Domain\ProductId;
use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;

class ProductIdType extends UuidType
{
    protected function typeClassName(): string
    {
        return ProductId::class;
    }
}
