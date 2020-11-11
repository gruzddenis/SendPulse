<?php

namespace App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 *
 * @package App\Controllers
 */
class Controller extends BaseController
{
    /**
     * @param mixed $data
     * @param int $status
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function response($data = null, int $status = 200, array $headers = []): JsonResponse
    {
        return (new JsonResponse($data, $status, $headers, JSON_UNESCAPED_UNICODE));
    }
}
