<?php

namespace App\Tests\Integration;

use App\Kernel;
use App\Shared\Domain\UuidGenerator;
use App\Tests\Shared\Infrastructure\PhpUnit\InfrastructureTestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\MockObject;

class IntegrationTestCase extends InfrastructureTestCase
{
    private UuidGenerator|MockInterface $uuidGenerator;

    protected function kernelClass(): string
    {
        return Kernel::class;
    }

    protected function shouldGenerateUuid(string $uuid): void
    {
        $this->uuidGenerator()
            ->expects($this->once())
            ->method('generate')
            ->willReturn($uuid);
    }

    protected function uuidGenerator(): UuidGenerator|MockObject
    {
        return $this->uuidGenerator = $this->uuidGenerator ?? $this->createMock(UuidGenerator::class);
    }
}
