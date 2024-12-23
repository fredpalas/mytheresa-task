<?php

namespace App\Shared\Infrastructure\EventListener;

use App\Shared\Infrastructure\Factory\NormalizerFactory;
use App\Shared\Infrastructure\Http\ApiResponse;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Throwable;

class ExceptionListener
{
    /**
     * @var NormalizerFactory
     */
    private $normalizerFactory;

    /**
     * ExceptionListener constructor.
     *
     * @param NormalizerFactory $normalizerFactory
     */
    public function __construct(NormalizerFactory $normalizerFactory)
    {
        $this->normalizerFactory = $normalizerFactory;
    }

    /**
     * @param ExceptionEvent $event
     * @throws ExceptionInterface
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $response = $this->createApiResponse($exception);
            $event->setResponse($response);
        }
    }

    /**
     * Creates the ApiResponse from any Exception
     *
     * @param Throwable $exception
     *
     * @return ApiResponse
     * @throws ExceptionInterface
     */
    private function createApiResponse(Throwable $exception)
    {
        $normalizer = $this->normalizerFactory->getNormalizer($exception);
        $statusCode = ($exception instanceof HttpExceptionInterface)
            ? $exception->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        try {
            $errors = $normalizer ? $normalizer->normalize($exception) : [];
        } catch (Exception $e) {
            $errors = [];
        }

        return new ApiResponse($exception->getMessage(), null, $errors, $statusCode);
    }
}
