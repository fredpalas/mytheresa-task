<?php

namespace App\Tests\Unit\Shop\Promotion\Application\Find;

use App\Shop\Promotion\Application\Find\FindAllPromotionsQuery;
use App\Shop\Promotion\Application\Find\AllPromotionFinder;
use App\Shop\Promotion\Domain\PromotionRepository;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Unit\Shop\Promotion\Domain\PromotionMother;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class FindAllPromotionsQueryHandlerTest extends UnitTestCase
{
    private PromotionRepository|MockInterface $repository;
    private AllPromotionFinder $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->mock(PromotionRepository::class);
        $this->handler = new AllPromotionFinder($this->repository);
    }

    public function testShouldFindAllPromotions(): void
    {
        $promotion = PromotionMother::create();

        $this->repository->shouldReceive('searchAll')
            ->once()
            ->andReturn([$promotion]);

        $promotions = $this->handler->__invoke();

        $this->assertCount(1, $promotions);
        $this->assertIsSimilar($promotion, $promotions->first());
    }
}
