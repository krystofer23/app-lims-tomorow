<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            DB::commit();
            return $this->sendSuccess('Usuario creado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
