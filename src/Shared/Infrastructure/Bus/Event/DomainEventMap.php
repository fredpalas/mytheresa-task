<?php

namespace App\Shared\Infrastructure\Bus\Event;

use Symfony\Component\DependencyInjection\ServiceLocator;

class DomainEventMap
{
    private array $mapping;

    public function __construct(ServiceLocator $locator)
    {
        $this->mapping = $locator->getProvidedServices();
    }

    /**
     * @throws \RuntimeException
     */
    public function for(string $name): string
    {
        if (!isset($this->mapping[$name])) {
            throw new \RuntimeException("The Domain Event Class for <$name> doesn't exists");
        }

        return $this->mapping[$name];
    }
}
