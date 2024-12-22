<?php

namespace App\Shop\Product\Application\Find;

use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\FilterOperator;
use App\Shared\Domain\Criteria\Filters;
use App\Shared\Domain\Criteria\Order;
use App\Shop\Product\Domain\ProductRepository;
use App\Shop\Product\Domain\Read\ProductCollection;
use App\Shop\Product\Domain\Read\ProductWithPromotion;
use App\Shop\Promotion\Application\Find\AllPromotionFinder;

use function Lambdish\Phunctional\map;

class FindProductsWithPromotionAppliedQueryHandler implements QueryHandler
{
    private const LIMIT = 5;

    public function __construct(
        private ProductRepository $repository,
        private AllPromotionFinder $allPromotionFinder
    ) {
    }

    public function __invoke(
        FindProductsWithPromotionAppliedQuery $query
    ): FindProductsWithPromotionAppliedQueryResponse {
        $promotions = $this->allPromotionFinder->__invoke();
        $filters = [];
        if ($query->category) {
            $filters[] = [
                'field' => 'category.value',
                'operator' => '=',
                'value' => $query->category,
            ];
        }
        if ($query->priceLessThan) {
            $filters[] = [
                'field' => 'price.value',
                'operator' => FilterOperator::LT,
                'value' => $query->priceLessThan,
            ];
        }
        $page = $query->page;
        $offset = $page === null ? (self::LIMIT === null ? 0 : null) : ($page - 1) * self::LIMIT;
        $criteria = new Criteria(
            Filters::fromValues($filters),
            Order::none(),
            $offset ?? 0,
            self::LIMIT
        );

        $criteriaCount = new Criteria(
            Filters::fromValues($filters),
            Order::none(),
            0,
            0
        );

        $products = $this->repository->searchByCriteria($criteria);

        $total = $this->repository->countByCriteria($criteriaCount);

        return new FindProductsWithPromotionAppliedQueryResponse(
            new ProductCollection(
                map(
                    fn ($product) => ProductWithPromotion::fromDomain($product, $promotions),
                    $products
                )
            ),
            $total,
            $page
        );
    }
}
