<?php

namespace App\Shop\Product\Domain\Read;

class Price
{
    public function __construct(
        public readonly int $original,
        public readonly int $final,
        public readonly ?string $discountPercentage = null,
        public readonly string $currency = 'EUR'
    ) {
    }
}
