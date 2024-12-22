<?php

namespace App\DataFixtures;

use App\Shared\Domain\UuidGenerator;
use App\Shop\Product\Domain\Product;
use App\Shop\Product\Domain\ProductCategory;
use App\Shop\Product\Domain\ProductId;
use App\Shop\Product\Domain\ProductName;
use App\Shop\Product\Domain\ProductPrice;
use App\Shop\Product\Domain\ProductSku;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ProductsFixtures extends Fixture implements FixtureGroupInterface
{
    private $products = [
        [
            "sku" => "000001",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => 89000,
        ],
        [
            "sku" => "000002",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => 99000,
        ],
        [
            "sku" => "000003",
            "name" => "Ashlington leather ankle boots",
            "category" => "boots",
            "price" => 71000,
        ],
        [
            "sku" => "000004",
            "name" => "Naima embellished suede sandals",
            "category" => "sandals",
            "price" => 79500,
        ],
        [
            "sku" => "000005",
            "name" => "Nathane leather sneakers",
            "category" => "sneakers",
            "price" => 59000,
        ],
    ];

    public function __construct(private UuidGenerator $generator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->products as $product) {
            $productEntity = new Product(
                new ProductId($this->generator->generate()),
                new ProductSku($product['sku']),
                new ProductName($product['name']),
                new ProductCategory($product['category']),
                new ProductPrice($product['price'])
            );

            $manager->persist($productEntity);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init'];
    }
}
