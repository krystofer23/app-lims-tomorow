<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Imports\TestImport;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TestImportApiController extends Controller
{
    public function import(Request $request): JsonResponse
    {
        try {
            $import = new TestImport();
            Excel::import($import, $request->file('file'));

            return $this->sendResponse([
                'imports' => $import->imports,
                'errors' => $import->errors,
            ], 'Importación con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
