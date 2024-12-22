<?php

namespace Shop\Http\Controllers\Products;

use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shop\Product\Application\Find\FindProductsWithPromotionAppliedQuery;
use App\Shop\Product\Application\Find\FindProductsWithPromotionAppliedQueryResponse;
use App\Shop\Product\Infrastructure\Symfony\Request\ProductFilterRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products', name: 'products', methods: ['GET'])]
class ListProductsController extends AbstractController
{
    public function __construct(private QueryBus $bus)
    {
    }

    public function __invoke(ProductFilterRequest $request): JsonResponse
    {
        /** @var FindProductsWithPromotionAppliedQueryResponse $products */
        $products = $this->bus->ask(
            new FindProductsWithPromotionAppliedQuery(
                $request->category(),
                $request->priceLessThan(),
                $request->page()
            )
        );

        return $this->json([
            'products' => $products->products->toArray(),
            'total' => $products->total,
            'page' => $products->page,
        ]);
    }
}
