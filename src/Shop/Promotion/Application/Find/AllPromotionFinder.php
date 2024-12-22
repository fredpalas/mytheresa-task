<?php

namespace App\Shop\Promotion\Application\Find;

use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shop\Promotion\Domain\PromotionCollection;
use App\Shop\Promotion\Domain\PromotionRepository;

class AllPromotionFinder implements QueryHandler
{
    public function __construct(private PromotionRepository $repository)
    {
    }

    public function __invoke(): PromotionCollection
    {
        return new PromotionCollection($this->repository->searchAll());
    }
}
