<?php

namespace App\Shop\Product\Application\Find;

use App\Shared\Domain\Bus\Query\Query;

readonly class FindProductsWithPromotionAppliedQuery implements Query
{
    public function __construct(
        public ?string $category = null,
        public ?int $priceLessThan = null,
        public int $page = 1,
    ) {
    }
}
