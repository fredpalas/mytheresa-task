<?php

namespace App\Tests\Unit\Shared\Domain\User;

use App\Shared\Domain\User\UserId;
use App\Tests\Shared\Domain\UuidMother;
use PHPUnit\Framework\TestCase;

class UserIdMother
{
    public static function create(?string $value = null): UserId
    {
        return new UserId($value ?? UuidMother::create());
    }
}
