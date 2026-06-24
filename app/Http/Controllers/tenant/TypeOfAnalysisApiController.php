<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\TypeOfAnalysis;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class TypeOfAnalysisApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $data = TypeOfAnalysis::query()
            ->when($search, fn($q) => $q->where('description', 'like', "%$search%"))
            ->paginate(15);

        return $this->sendResponse($data, 'Enviando tipo de analisis');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            TypeOfAnalysis::create([
                'description' => $request->description ?? null
            ]);

            DB::commit();
            return $this->sendSuccess('Tipo de analisis');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $typeOfAnalysis = TypeOfAnalysis::findOrFail($id);
            $typeOfAnalysis->update([
                'description' => $request->description ?? null
            ]);

            DB::commit();
            return $this->sendSuccess('Tipo de analisis');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $typeOfAnalysis = TypeOfAnalysis::findOrFail($id);
            $typeOfAnalysis->delete();

            DB::commit();
            return $this->sendSuccess('Tipo de analisis');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
