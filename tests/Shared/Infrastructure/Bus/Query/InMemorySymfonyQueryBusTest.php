<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Infrastructure\Bus\Query\InMemorySymfonyQueryBus;
use App\Shared\Infrastructure\Bus\Query\QueryNotRegisteredError;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery\MockInterface;
use RuntimeException;

final class InMemorySymfonyQueryBusTest extends UnitTestCase
{
    private InMemorySymfonyQueryBus|null $queryBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBus = new InMemorySymfonyQueryBus([$this->queryHandler()]);
    }

    /** @test */
    public function itShouldReturnAResponseSuccessfully(): void
    {
        $this->expectException(RuntimeException::class);

        $this->queryBus->ask(new FakeQuery());
    }

    /** @test */
    public function itShouldRaiseAnExceptionDispatchingANonRegisteredQuery(): void
    {
        $this->expectException(QueryNotRegisteredError::class);

        $this->queryBus->ask($this->query());
    }

    private function queryHandler(): object
    {
        return new class () {
            public function __invoke(FakeQuery $query): void
            {
                throw new RuntimeException('This works fine!');
            }
        };
    }

    private function query(): Query|MockInterface
    {
        return $this->mock(Query::class);
    }
}
