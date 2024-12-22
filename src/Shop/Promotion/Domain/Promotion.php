<?php

namespace App\Shop\Promotion\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shop\Product\Domain\Product;

class Promotion extends AggregateRoot
{
    public function __construct(
        private readonly PromotionId $id,
        private readonly PromotionType $type,
        private readonly PromotionPercentage $percentage,
        private readonly PromotionApplyTo $applyTo
    ) {
    }

    public static function create(
        PromotionId $id,
        PromotionType $type,
        PromotionPercentage $percentage,
        PromotionApplyTo $applyTo
    ): self {
        return new self($id, $type, $percentage, $applyTo);
    }

    public function apply(string $sku, string $category, int $price): int
    {
        return match ($this->type->value()) {
            PromotionType::CATEGORY => $this->discountCategory($category, $price),
            PromotionType::SKU => $this->discountSku($sku, $price),
            default => $price,
        };
    }

    public function id(): PromotionId
    {
        return $this->id;
    }

    public function type(): PromotionType
    {
        return $this->type;
    }

    public function percentage(): PromotionPercentage
    {
        return $this->percentage;
    }

    public function applyTo(): PromotionApplyTo
    {
        return $this->applyTo;
    }

    private function discountCategory(string $category, int $price): int
    {
        if ($this->applyTo->value() === $category) {
            return round($price - ($price * $this->percentage->value() / 100));
        }

        return $price;
    }

    private function discountSku(string $sku, int $price): int
    {
        if ($this->applyTo->value() === $sku) {
            return round($price - ($price * $this->percentage->value() / 100));
        }

        return $price;
    }
}
