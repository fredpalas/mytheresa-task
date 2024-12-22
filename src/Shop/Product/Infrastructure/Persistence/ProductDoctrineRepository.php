<?php

namespace App\Shop\Product\Infrastructure\Persistence;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;
use App\Shop\Product\Domain\Product;
use App\Shop\Product\Domain\ProductId;
use App\Shop\Product\Domain\ProductRepository;

class ProductDoctrineRepository extends DoctrineRepository implements ProductRepository
{
    public function save(Product $product): void
    {
        $this->persist($product);
    }

    public function searchAll(): array
    {
        return $this->repository(Product::class)->findAll();
    }

    public function search(ProductId $id): ?Product
    {
        return $this->repository(Product::class)->find($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        return $this->repository(Product::class)->matching($doctrineCriteria)->toArray();
    }

    public function countByCriteria(Criteria $criteria): int
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        return $this->repository(Product::class)->matching($doctrineCriteria)->count();
    }
}
