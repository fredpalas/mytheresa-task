<?php

namespace App\Shared\Domain;

class NoTypeCollection extends Collection
{
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return '';
    }
}
