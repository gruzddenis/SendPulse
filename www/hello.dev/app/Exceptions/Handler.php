<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 *
 * @package App\Exceptions
 */
class Handler
{
    /**
     * @param Exception $exception
     *
     * @return JsonResponse
     */
    public function render(Exception $exception): JsonResponse
    {
        $response = $this->prepareResponse($exception);

        return $response;
    }
    /**
     * @param Exception $exception
     *
     * @return JsonResponse
     */
    protected function prepareResponse(Exception $exception): JsonResponse
    {
        $message = 'Internal Server Error';
        $statusCode = 500;

        switch (true) {
            case $exception instanceof InvalidRequestException:
                $statusCode = $exception->getStatusCode();
                $message = $exception->getMessage();
                break;
            case $exception instanceof UnauthorizedException:
                $statusCode = $exception->getStatusCode();
                $message = $exception->getMessage();
                break;
            case $exception instanceof  NotFoundHttpException:
                $statusCode = $exception->getStatusCode();
                $message = $exception->getMessage();
                break;
        }

        return $response = new JsonResponse($message, $statusCode, [], JSON_UNESCAPED_UNICODE);;
    }
}
