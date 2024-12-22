<?php

namespace App\Shop\Product\Domain\Read;

use App\Shared\Domain\Collection;

class ProductCollection extends Collection
{
    protected function type(): string
    {
        return ProductWithPromotion::class;
    }

    public function toArray(): array
    {
        return $this->arrayMap(fn (ProductWithPromotion $product) => $product->toArray());
    }
}
