<?php

namespace App\Shop\Promotion\Infrastructure\Persistence;

use App\Shared\Infrastructure\Persistence\DoctrineRepository;
use App\Shop\Promotion\Domain\Promotion;
use App\Shop\Promotion\Domain\PromotionId;
use App\Shop\Promotion\Domain\PromotionRepository;

class PromotionDoctrineRepository extends DoctrineRepository implements PromotionRepository
{
    public function save(Promotion $promotion): void
    {
        $this->persist($promotion);
    }

    public function search(PromotionId $id): ?Promotion
    {
        return $this->repository(Promotion::class)->find($id);
    }

    public function searchAll(): array
    {
        return $this->repository(Promotion::class)->findAll();
    }
}
