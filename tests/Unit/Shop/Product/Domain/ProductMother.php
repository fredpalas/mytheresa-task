<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shop\Product\Domain;

use App\Shop\Product\Domain\Product;
use App\Shop\Product\Domain\ProductCategory;
use App\Shop\Product\Domain\ProductId;
use App\Shop\Product\Domain\ProductName;
use App\Shop\Product\Domain\ProductPrice;
use App\Shop\Product\Domain\ProductSku;
use App\Tests\Shared\Domain\MotherCreator;
use App\Tests\Shared\Domain\UuidMother;
use App\Tests\Shared\Domain\WordMother;

class ProductMother
{
    public static function create(
        ?string $category = null,
    ): Product {
        return Product::create(
            new ProductId(UuidMother::create()),
            new ProductSku(WordMother::create()),
            new ProductName(WordMother::create()),
            new ProductCategory($category ?? MotherCreator::random()->word()),
            ProductPrice::fromInt(MotherCreator::random()->numberBetween(100, 10000)),
        );
    }

    public static function fromPrimitive(array $array): Product
    {
        return Product::create(
            isset($array['id']) ? new ProductId($array['id']) : new ProductId(UuidMother::create()),
            new ProductSku($array['sku']),
            new ProductName($array['name']),
            new ProductCategory($array['category']),
            ProductPrice::fromInt($array['price']),
        );
    }
}
