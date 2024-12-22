<?php

namespace App\Shop\Product\Infrastructure\Symfony\Request;

use App\Shared\Infrastructure\Http\DTO\RequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ProductFilterRequest implements RequestDTO
{
    #[Assert\Type('string', message: 'The value {{ value }} is not a valid' . ' {{ type }}.')]
    private ?string $category;
    #[Assert\Type('integer', message: 'The value {{ value }} is not a valid' . ' {{ type }}.')]
    #[Assert\PositiveOrZero(message: 'The value {{ value }} should be greater than or equal to 0.')]
    private ?int $priceLessThan;
    #[Assert\Type('integer', message: 'The value {{ value }} is not a valid' . ' {{ type }}.')]
    #[Assert\Positive(message: 'The value {{ value }} should be greater than 0.')]
    private int $page;

    public function __construct(Request $request)
    {
        $this->category = $request->query->get('category', null);
        $this->priceLessThan = $request->query->get('priceLessThan', null);
        $this->page = $request->query->get('page', 1);
    }

    public function category(): ?string
    {
        return $this->category;
    }

    public function priceLessThan(): ?int
    {
        return $this->priceLessThan;
    }

    public function page(): int
    {
        return $this->page;
    }
}
