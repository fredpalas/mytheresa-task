<?php

namespace App\Shared\Infrastructure\Bus\Event;

use RuntimeException;
use Symfony\Component\Messenger\Exception\UnrecoverableExceptionInterface;

class DomainEventNotSerializableException extends RuntimeException implements UnrecoverableExceptionInterface
{
}
