<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function sendSuccess($msg, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $msg
        ], $code);
    }

    public function sendError($msg, $code = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $msg
        ], $code);
    }

    public function sendResponse($data, $msg, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $msg
        ], $code);
    }
}
