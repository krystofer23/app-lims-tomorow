<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Imports\ImportMatriz;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportApiController extends Controller
{
    public function import(Request $request): JsonResponse
    {
        try {
            if (!$request->hasFile('file')) {
                return $this->sendError('No se adjuntó ningún archivo.');
            }

            Excel::import(new ImportMatriz, $request->file('file'));

            return $this->sendSuccess('Importación con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
