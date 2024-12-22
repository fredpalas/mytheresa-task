<?php

namespace App\Tests\Unit\Shop\Product\Application\Find;

use App\Shop\Product\Application\Find\FindProductsWithPromotionAppliedQuery;
use App\Shop\Product\Application\Find\FindProductsWithPromotionAppliedQueryHandler;
use App\Shop\Product\Domain\ProductRepository;
use App\Shop\Product\Domain\Read\Price;
use App\Shop\Product\Domain\Read\ProductCollection;
use App\Shop\Product\Domain\Read\ProductWithPromotion;
use App\Shop\Promotion\Application\Find\AllPromotionFinder;
use App\Shop\Promotion\Domain\PromotionApplyTo;
use App\Shop\Promotion\Domain\PromotionPercentage;
use App\Shop\Promotion\Domain\PromotionRepository;
use App\Shop\Promotion\Domain\PromotionType;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Unit\Shop\Product\Domain\ProductMother;
use App\Tests\Unit\Shop\Promotion\Domain\PromotionMother;
use Mockery\MockInterface;

class FindProductsWithPromotionAppliedQueryHandlerTest extends UnitTestCase
{
    private ProductRepository|MockInterface $repository;
    private FindProductsWithPromotionAppliedQueryHandler $handler;
    private PromotionRepository|MockInterface $promotionRepository;
    private AllPromotionFinder $promotionsFinder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->mock(ProductRepository::class);
        $this->promotionRepository = $this->mock(PromotionRepository::class);
        $this->promotionsFinder = new AllPromotionFinder($this->promotionRepository);
        $this->handler = new FindProductsWithPromotionAppliedQueryHandler(
            $this->repository,
            $this->promotionsFinder
        );
    }

    public function testShouldFindProductsWithPromotionApplied(): void
    {
        $products = $this->productsBootLessThan90000();
        $productCollection = $this->productResultWithDiscount();
        $promotion = PromotionMother::create(
            type: PromotionType::category(),
            percentage: new PromotionPercentage(30),
            applyTo: PromotionApplyTo::create('boots')
        );
        $promotion2 = PromotionMother::create(
            type: PromotionType::sku(),
            percentage: new PromotionPercentage(15),
            applyTo: PromotionApplyTo::create('000003')
        );
        $this->promotionRepository->shouldReceive('searchAll')
            ->once()
            ->andReturn([$promotion, $promotion2]);
        $this->repository->shouldReceive('searchByCriteria')
            ->once()
            ->andReturn($products);
        $this->repository->shouldReceive('countByCriteria')
            ->once()
            ->andReturn(2);
        $query = new FindProductsWithPromotionAppliedQuery(
            'boots',
            90000
        );
        $response = $this->handler->__invoke($query);
        $this->assertIsSimilar(
            $productCollection,
            $response->products,
        );

        $this->assertEquals(2, $response->total);
        $this->assertEquals(1, $response->page);
    }

    public function testShouldFindProductsWithPromotionAppliedDiscountSku(): void
    {
        $products = $this->productsBootLessThan90000();
        $productCollection = $this->productResultOnlySku();
        $promotion2 = PromotionMother::create(
            type: PromotionType::sku(),
            percentage: new PromotionPercentage(15),
            applyTo: PromotionApplyTo::create('000003')
        );
        $this->promotionRepository->shouldReceive('searchAll')
            ->once()
            ->andReturn([$promotion2]);
        $this->repository->shouldReceive('searchByCriteria')
            ->once()
            ->andReturn($products);
        $this->repository->shouldReceive('countByCriteria')
            ->once()
            ->andReturn(2);
        $query = new FindProductsWithPromotionAppliedQuery(
            'boots',
            90000
        );
        $response = $this->handler->__invoke($query);
        $this->assertIsSimilar(
            $productCollection,
            $response->products
        );

        $this->assertEquals(2, $response->total);
        $this->assertEquals(1, $response->page);
    }

    public function productsBootLessThan90000(): array
    {
        return [
            ProductMother::fromPrimitive([
                "sku" => "000001",
                "name" => "BV Lean leather ankle boots",
                "category" => "boots",
                "price" => 89000,
            ]),
            ProductMother::fromPrimitive([
                "sku" => "000003",
                "name" => "Ashlington leather ankle boots",
                "category" => "boots",
                "price" => 71000,
            ]),
        ];
    }

    public function productResultWithDiscount(): ProductCollection
    {
        return ProductCollection::from(
            [
                new ProductWithPromotion(
                    sku: '000001',
                    name: 'BV Lean leather ankle boots',
                    category: 'boots',
                    price: new Price(
                        original: 89000,
                        final: 62300,
                        discountPercentage: '30%',
                        currency: 'EUR'
                    )
                ),
                new ProductWithPromotion(
                    sku: '000003',
                    name: 'Ashlington leather ankle boots',
                    category: 'boots',
                    price: new Price(
                        original: 71000,
                        final: 49700,
                        discountPercentage: '30%',
                        currency: 'EUR'
                    )
                ),
            ]
        );
    }

    public function productResultOnlySku(): ProductCollection
    {
        return ProductCollection::from(
            [
                new ProductWithPromotion(
                    sku: '000001',
                    name: 'BV Lean leather ankle boots',
                    category: 'boots',
                    price: new Price(
                        original: 89000,
                        final: 89000,
                        discountPercentage: null,
                        currency: 'EUR'
                    )
                ),
                new ProductWithPromotion(
                    sku: '000003',
                    name: 'Ashlington leather ankle boots',
                    category: 'boots',
                    price: new Price(
                        original: 71000,
                        final: 60350,
                        discountPercentage: '15%',
                        currency: 'EUR'
                    )
                ),
            ]
        );
    }
}
