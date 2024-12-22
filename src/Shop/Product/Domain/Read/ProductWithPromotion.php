<?php

namespace App\Shop\Product\Domain\Read;

use App\Shop\Product\Domain\Product;
use App\Shop\Product\Domain\Read\Price;
use App\Shop\Promotion\Domain\Promotion;
use App\Shop\Promotion\Domain\PromotionCollection;

readonly class ProductWithPromotion
{
    public function __construct(
        public string $sku,
        public string $name,
        public string $category,
        public Price $price
    ) {
    }

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category' => $this->category,
            'price' => [
                'original' => $this->price->original,
                'final' => $this->price->final,
                'discount_percentage' => $this->price->discountPercentage,
                'currency' => $this->price->currency,
            ],
        ];
    }

    public static function fromDomain(Product $product, ?PromotionCollection $promotions = null): self
    {
        $price = $product->price()->value();
        $discountPercentage = null;
        $promotions->each(function (Promotion $promotion) use ($product, &$price, &$discountPercentage) {
            $priceDiscount = $promotion->apply(
                $product->sku()->value(),
                $product->category()->value(),
                $product->price()->value()
            );

            if ($priceDiscount < $price) {
                $discountPercentage = $promotion->percentage()->value();
                $price = $priceDiscount;
            }
        });

        return new self(
            sku: $product->sku()->value(),
            name: $product->name()->value(),
            category: $product->category()->value(),
            price: new Price(
                original: $product->price()->value(),
                final: $price,
                discountPercentage: $discountPercentage ? $discountPercentage . '%' : null,
                currency: 'EUR'
            )
        );
    }
}
