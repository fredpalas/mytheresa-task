<?php

namespace App\Tests\Integration\Shop\Promotion\Infrastructure\Persistence;

use App\Shop\Promotion\Domain\Promotion;
use App\Shop\Promotion\Domain\PromotionCollection;
use App\Shop\Promotion\Infrastructure\Persistence\PromotionDoctrineRepository;
use App\Tests\Integration\IntegrationTestCase;
use App\Tests\Unit\Shop\Promotion\Domain\PromotionMother;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use PHPUnit\Framework\TestCase;

class PromotionDoctrineRepositoryTest extends IntegrationTestCase
{
    use RecreateDatabaseTrait;

    private PromotionDoctrineRepository $repository;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        parent::setUp();

        $this->repository = $this->service(PromotionDoctrineRepository::class);
        $this->em = $this->service('doctrine')->getManager();
    }

    public function testItShouldSaveAPromotion(): void
    {
        $promotion = PromotionMother::create();
        $this->repository->save($promotion);

        $this->assertIsSimilar(
            $promotion,
            $this->em->getRepository(Promotion::class)->find($promotion->id())
        );
    }

    public function testShouldSearch(): void
    {
        $promotion = PromotionMother::create();
        $this->repository->save($promotion);

        $this->assertIsSimilar(
            $promotion,
            $this->repository->search($promotion->id())
        );
    }

    public function testItShouldSearchAllPromotions(): void
    {
        $promotions = [
            PromotionMother::create(),
            PromotionMother::create(),
            PromotionMother::create()
        ];

        foreach ($promotions as $promotion) {
            $this->repository->save($promotion);
            $promotion->pullDomainEvents();
        }

        $this->assertIsSimilar(
            new PromotionCollection($promotions),
            new PromotionCollection($this->repository->searchAll())
        );
    }
}
