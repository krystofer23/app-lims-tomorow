<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\TypeOfSamples;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeOfSamplesApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $data = TypeOfSamples::query()
            ->when($search, fn($q) => $q->where('description', 'like', "%$search%"))
            ->orderBy('id', 'desc')
            ->paginate(15);

        return $this->sendResponse($data, 'Enviando tipo de analisis');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            TypeOfSamples::create([
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

            $typeOfSamples = TypeOfSamples::findOrFail($id);
            $typeOfSamples->update([
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

            $typeOfSamples = TypeOfSamples::findOrFail($id);
            $typeOfSamples->delete();

            DB::commit();
            return $this->sendSuccess('Tipo de analisis');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
