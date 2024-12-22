<?php

namespace App\Shop\Promotion\Domain;

interface PromotionRepository
{
    public function save(Promotion $promotion): void;

    public function search(PromotionId $id): ?Promotion;

    public function searchAll(): array;
}
