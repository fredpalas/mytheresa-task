<?php

namespace App\Shared\Infrastructure\Http\Transformer;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

use function json_decode;

class RequestBodyTransformer
{
    public function transform(Request $request): void
    {
        $request->request->replace(
            json_decode($request->getContent(), true) ?? []
        );
    }
}
