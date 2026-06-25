<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Matrix;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatrixApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $data = Matrix::query()
            ->with(['typeOfSample'])
            ->when($search, function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                ->orWhereHas('typeOfSample', fn($q) => $q->where('description', 'like', "%$search%"));
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return $this->sendResponse($data, 'Enviando tipo de analisis');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            Matrix::create([
                'description' => $input['description'] ?? null,
                'type_of_sample_id' => $input['type_of_sample_id'] ?? null,
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

            $input = $request->all();

            $matrix = Matrix::findOrFail($id);
            $matrix->update([
                'description' => $input['description'] ?? null,
                'type_of_sample_id' => $input['type_of_sample_id'] ?? null,
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

            $matrix = Matrix::findOrFail($id);
            $matrix->delete();

            DB::commit();
            return $this->sendSuccess('Tipo de analisis');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
