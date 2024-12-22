<?php

namespace App\Shared\Infrastructure\CodeGenerators;

use App\Shared\Domain\CodeGenerator;

class Hmac implements CodeGenerator
{
    public function __construct(
        private string $key,
        private string $algorithm = 'haval128,3'
    ) {
    }

    public function generate(string $text): string
    {
        return hash_hmac($this->algorithm, $text, $this->key);
    }
}
