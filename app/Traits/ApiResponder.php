<?php
namespace App\Traits;
use Illuminate\Http\JsonResponse;

/**
 * 
 */
trait ApiResponder
{
    protected function apiResponse($data = null, $message = null, $success = true, $statusCode = JsonResponse::HTTP_OK){
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $statusCode);
    }

    protected function successResponse($data = null, $message = null, $statusCode = JsonResponse::HTTP_OK){
        return $this->apiResponse($data, $message, true, $statusCode);
    }

    protected function errorResponse($message,$statusCode)
    {
        return $this->apiResponse(null, $message, false, $statusCode);
    }
}
