<?php

namespace App\Shop\Product\Domain;

use App\Shared\Domain\Criteria\Criteria;
use App\Shop\Product\Domain\Product;
use App\Shop\Product\Domain\ProductId;

interface ProductRepository
{
    public function save(Product $product): void;

    public function searchAll(): array;

    public function search(ProductId $id): ?Product;

    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;
}
