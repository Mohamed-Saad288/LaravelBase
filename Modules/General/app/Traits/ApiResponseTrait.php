<?php

namespace Modules\General\app\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * @param $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public function sendResponse($data, string $message = 'success', int $status = 200): JsonResponse
    {
        $response = [
            'status' => true,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($response, $status);
    }
}
