<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Company;
use App\Models\tenant\Conditions;
use App\Models\tenant\Essays;
use App\Models\tenant\Matriz;
use App\Models\tenant\Methodologies;
use App\Models\tenant\Services;
use App\Models\tenant\UnitsMeasurement;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListApiController extends Controller
{
    public function matrizDescription(): JsonResponse
    {
        $data = Matriz::query()
            ->pluck('description')
            ->filter()
            ->unique()
            ->values();

        return $this->sendResponse($data, 'Enviando datos de matrices descripcion');
    }

    public function companies(Request $request): JsonResponse
    {
        $query = $request->input('query', null);

        $data = Company::query()
            ->when($request->filled('query'), function ($q) use ($query) {
                $q->where('ruc', 'like', "%$query%")
                    ->orWhere('business_name', 'like', "%$query%");
            })
            ->where('state', true)
            ->paginate(15);

        return $this->sendResponse($data, 'Enviando empresas');
    }

    public function conditions(Request $request): JsonResponse
    {
        $query = $request->input('query', null);

        $data = Conditions::query()
            ->when($request->filled('query'), function ($q) use ($query) {
                $q->where('description', 'like', "%$query%");
            })
            ->paginate(15);

        return $this->sendResponse($data, 'Enviando condiciones');
    }

    public function unitsMeasurement(Request $request): JsonResponse
    {
        $query = $request->input('query', null);

        $data = UnitsMeasurement::query()
            ->when($request->filled('query'), function ($q) use ($query) {
                $q->where('description', 'like', "%$query%");
            })
            ->paginate(15);

        return $this->sendResponse($data, 'Enviando unidades de medida');
    }

    public function methodologies(Request $request): JsonResponse
    {
        $query = $request->input('query', null);

        $data = Methodologies::query()
            ->when($request->filled('query'), function ($q) use ($query) {
                $q->where('description', 'like', "%$query%");
            })
            ->paginate(15);

        return $this->sendResponse($data, 'Enviando metodologias');
    }

    public function essays(Request $request): JsonResponse
    {
        try {
            $query = $request->input('query', null);

            $data = Essays::query()
                ->when($request->filled('query'), function ($q) use ($query) {
                    $q->where('description', 'like', "%$query%");
                })
                ->paginate(5);

            return $this->sendResponse($data, 'Enviando ensayos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function services(Request $request): JsonResponse
    {
        try {
            $query = $request->input('query', null);

            $data = Services::query()
                ->when($request->filled('query'), function ($q) use ($query) {
                    $q->where('description', 'like', "%$query%");
                })
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando servicios');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
