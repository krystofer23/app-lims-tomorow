<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Departament;
use App\Models\tenant\Disctric;
use App\Models\tenant\Pronvince;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UbigeoApiController extends Controller
{
    public function departments(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $data = Departament::query()
            ->when($request->filled('search'), function ($q) use ($search) {
                $q->where('departamento', 'like', "%{$search}%");
            })
            ->orderBy('departamento')
            ->get();

        return $this->sendResponse($data, 'Enviando departamentos');
    }

    public function provinces(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $departamentoId = $request->input('departamento_id');

        $data = Pronvince::query()
            ->when($request->filled('search'), function ($q) use ($search) {
                $q->where('provincia', 'like', "%{$search}%");
            })
            ->when($request->filled('departamento_id'), function ($q) use ($departamentoId) {
                $q->whereHas('departament', fn($q) => $q->where('departamento', $departamentoId));
            })
            ->orderBy('provincia')
            ->get();

        return $this->sendResponse($data, 'Enviando provincias');
    }

    public function districts(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $provinciaId = $request->input('provincia_id');

        $data = Disctric::query()
            ->when($request->filled('search'), function ($q) use ($search) {
                $q->where('distrito', 'like', "%{$search}%");
            })
            ->when($request->filled('provincia_id'), function ($q) use ($provinciaId) {
                $q->whereHas('province', fn($q) => $q->where('provincia', $provinciaId));
            })
            ->orderBy('distrito')
            ->get();

        return $this->sendResponse($data, 'Enviando distritos');
    }
}
