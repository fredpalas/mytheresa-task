<?php

namespace App\Shared\Infrastructure\Http;

use App\Shared\Infrastructure\Http\DTO\RequestDTO;
use App\Shared\Infrastructure\Http\Transformer\RequestBodyTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValueResolver implements ValueResolverInterface
{
    /**
     * @var RequestBodyTransformer
     */
    private $requestBodyTransformer;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        RequestBodyTransformer $requestBodyTransformer,
        ValidatorInterface $validator
    ) {
        $this->requestBodyTransformer = $requestBodyTransformer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (!class_exists($argument->getType())) {
            return false;
        }
        $reflectionClass = new \ReflectionClass($argument->getType());
        if ($reflectionClass->implementsInterface(RequestDTO::class)) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        if (!$this->supports($request, $argument)) {
            return;
        }

        $this->requestBodyTransformer->transform($request);

        $class = $argument->getType();
        $dto = new $class($request);

        $errors = $this->validator->validate($dto);

        if (\count($errors) > 0) {
            $message = [];
            foreach ($errors as $error) {
                $message[$error->getPropertyPath()][] = $error->getMessage();
            }

            throw new BadRequestHttpException(json_encode($message));
        }

        yield $dto;
    }
}
