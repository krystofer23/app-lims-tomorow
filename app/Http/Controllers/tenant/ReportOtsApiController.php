<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\OtsGenerate;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportOtsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = OtsGenerate::query()
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando OTs');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
