<?php

namespace App\Shared\Infrastructure\Symfony\Request;

use App\Shared\Domain\Criteria\FilterOperator;
use App\Shared\Infrastructure\Http\DTO\RequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class FiltersRequest implements RequestDTO
{
    #[Assert\All(
        [
            new Assert\Collection(
                [
                    'field' => [
                        new Assert\NotBlank(),
                        new Assert\Type(type: 'string'),
                    ],
                    'operator' => [
                        new Assert\NotBlank(),
                        new Assert\Type(type: 'string'),
                        new Assert\Choice(choices: [
                            FilterOperator::EQUAL,
                            FilterOperator::NOT_CONTAINS,
                            FilterOperator::CONTAINS,
                            FilterOperator::GT,
                            FilterOperator::LT,
                            FilterOperator::IN,
                            FilterOperator::NIN,
                        ]),
                    ],
                    'value' => new Assert\NotNull(),
                ]
            ),
        ]
    )]
    #[Assert\Valid]
    private array $filters;

    #[Assert\All(
        [
            new Assert\Type(type: 'string'),
        ]
    )]
    #[Assert\Type(type: 'array')]
    #[Assert\Valid]
    private array $fields;

    public function __construct(Request $request)
    {
        $all = $request->query->all();
        $this->filters = $all['filters'] ?? [];
        $this->fields = $all['fields'] ?? [];
    }

    public function fields()
    {
        return $this->fields ?? [];
    }

    public function filters(): array
    {
        return $this->filters;
    }
}
