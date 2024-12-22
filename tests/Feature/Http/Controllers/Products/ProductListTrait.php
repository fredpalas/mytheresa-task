<?php

namespace App\Tests\Feature\Http\Controllers\Products;

trait ProductListTrait
{
    private array $productsAllDiscount = [
        [
            "sku" => "000001",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 89000,
                "final" => 62300,
                "discount_percentage" => "30%",
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000002",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 99000,
                "final" => 69300,
                "discount_percentage" => "30%",
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000003",
            "name" => "Ashlington leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 71000,
                "final" => 49700,
                "discount_percentage" => "30%",
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000004",
            "name" => "Naima embellished suede sandals",
            "category" => "sandals",
            "price" => [
                "original" => 79500,
                "final" => 79500,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000005",
            "name" => "Nathane leather sneakers",
            "category" => "sneakers",
            "price" => [
                "original" => 59000,
                "final" => 59000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
    ];

    private array $productsNotDiscount = [
        [
            "sku" => "000001",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 89000,
                "final" => 89000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000002",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 99000,
                "final" => 99000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000003",
            "name" => "Ashlington leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 71000,
                "final" => 71000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000004",
            "name" => "Naima embellished suede sandals",
            "category" => "sandals",
            "price" => [
                "original" => 79500,
                "final" => 79500,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000005",
            "name" => "Nathane leather sneakers",
            "category" => "sneakers",
            "price" => [
                "original" => 59000,
                "final" => 59000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
    ];

    private array $productsSkuDiscount = [
        [
            "sku" => "000001",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 89000,
                "final" => 89000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000002",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 99000,
                "final" => 99000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000003",
            "name" => "Ashlington leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 71000,
                "final" => 60350,
                "discount_percentage" => "15%",
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000004",
            "name" => "Naima embellished suede sandals",
            "category" => "sandals",
            "price" => [
                "original" => 79500,
                "final" => 79500,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000005",
            "name" => "Nathane leather sneakers",
            "category" => "sneakers",
            "price" => [
                "original" => 59000,
                "final" => 59000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
    ];

    private array $productsLessThan9000 = [
        [
            "sku" => "000001",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 89000,
                "final" => 89000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000003",
            "name" => "Ashlington leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 71000,
                "final" => 71000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000004",
            "name" => "Naima embellished suede sandals",
            "category" => "sandals",
            "price" => [
                "original" => 79500,
                "final" => 79500,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000005",
            "name" => "Nathane leather sneakers",
            "category" => "sneakers",
            "price" => [
                "original" => 59000,
                "final" => 59000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
    ];

    private array $productOnlyBootsCategory = [
        [
            "sku" => "000001",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 89000,
                "final" => 89000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000002",
            "name" => "BV Lean leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 99000,
                "final" => 99000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
        [
            "sku" => "000003",
            "name" => "Ashlington leather ankle boots",
            "category" => "boots",
            "price" => [
                "original" => 71000,
                "final" => 71000,
                "discount_percentage" => null,
                "currency" => "EUR",
            ],
        ],
    ];
}
