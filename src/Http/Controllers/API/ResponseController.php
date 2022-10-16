<?php

namespace Orion\Larashared\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ResponseController extends Controller
{
    /**
     * Return success response.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendSuccess($result, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * Return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendError($error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (! empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
