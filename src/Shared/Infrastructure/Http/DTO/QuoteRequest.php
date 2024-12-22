<?php

namespace App\Shared\Infrastructure\Http\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class QuoteRequest implements RequestDTO
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var int
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Range(min=1, max=10)
     */
    private $limit;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->limit = (int) $request->query->get('limit', 10);
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
