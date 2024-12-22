<?php

namespace App\Tests\Integration\Shop\Product\Infrastructure\Persistence;

use App\Shop\Product\Domain\Product;
use App\Shop\Product\Infrastructure\Persistence\ProductDoctrineRepository;
use App\Tests\Integration\IntegrationTestCase;
use App\Tests\Shared\Domain\Criteria\CriteriaMother;
use App\Tests\Shared\Domain\Criteria\FilterMother;
use App\Tests\Shared\Domain\Criteria\FiltersMother;
use App\Tests\Unit\Shop\Product\Domain\ProductMother;
use Doctrine\ORM\EntityManager;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;

class ProductDoctrineRepositoryTest extends IntegrationTestCase
{
    use RecreateDatabaseTrait;

    private ProductDoctrineRepository $repository;
    private EntityManager $em;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        parent::setUp();
        $this->repository = $this->service(ProductDoctrineRepository::class);
        $this->em = $this->service('doctrine.orm.entity_manager');
    }

    public function testItShouldSaveAProduct(): void
    {
        $product = ProductMother::create();

        $this->repository->save($product);

        $this->clearUnitOfWork();
        $this->assertIsSimilar(
            $product,
            $this->em->getRepository(Product::class)->find($product->id())
        );
    }

    public function testItShouldSearch(): void
    {
        $product = ProductMother::create();
        $this->repository->save($product);
        $product->pullDomainEvents();

        $this->clearUnitOfWork();
        $this->assertIsSimilar(
            $product,
            $this->repository->search($product->id())
        );
    }

    public function testItShouldSearchAllProducts(): void
    {
        $product = ProductMother::create();
        $product2 = ProductMother::create();
        $this->repository->save($product);
        $this->repository->save($product2);
        $product->pullDomainEvents();
        $product2->pullDomainEvents();

        $this->clearUnitOfWork();
        $this->assertIsSimilar(
            [$product, $product2],
            $this->repository->searchAll()
        );
    }

    public function testItShouldSearchByCriteria(): void
    {
        $product = ProductMother::create();
        $product2 = ProductMother::create();
        $this->repository->save($product);
        $this->repository->save($product2);
        $product->pullDomainEvents();
        $product2->pullDomainEvents();

        $criteria = CriteriaMother::create(
            FiltersMother::createOne(
                FilterMother::fromValues(
                    [
                        'field' => 'id',
                        'operator' => '=',
                        'value' => $product->id(),
                    ]
                )
            )
        );

        $this->clearUnitOfWork();
        $this->assertIsSimilar(
            [$product],
            $this->repository->searchByCriteria($criteria)
        );
    }

    public function testItShouldCountByCriteria(): void
    {
        $product = ProductMother::create();
        $product2 = ProductMother::create(category: $product->category()->value());
        $product3 = ProductMother::create();
        $this->repository->save($product);
        $this->repository->save($product2);
        $this->repository->save($product3);
        $product->pullDomainEvents();
        $product2->pullDomainEvents();
        $product3->pullDomainEvents();

        $criteria = CriteriaMother::create(
            FiltersMother::createOne(
                FilterMother::fromValues(
                    [
                        'field' => 'category.value',
                        'operator' => '=',
                        'value' => $product->category()->value(),
                    ]
                )
            )
        );

        $this->clearUnitOfWork();
        $this->assertEquals(
            2,
            $this->repository->countByCriteria($criteria)
        );
    }
}
