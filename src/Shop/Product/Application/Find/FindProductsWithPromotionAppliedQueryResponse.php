<?php

namespace App\Shop\Product\Application\Find;

use App\Shared\Domain\Bus\Query\Response;
use App\Shop\Product\Domain\Read\ProductCollection;

readonly class FindProductsWithPromotionAppliedQueryResponse implements Response
{
    public function __construct(
        public ProductCollection $products,
        public int $total,
        public int $page,
    ) {
    }
}
