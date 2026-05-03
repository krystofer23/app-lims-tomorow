<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Company;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConsultingRucApiController extends Controller
{
    public function consulting(?string $ruc = null): JsonResponse
    {
        if (empty($ruc) || ! preg_match('/^\d{11}$/', $ruc)) {
            return $this->sendError('Debe proporcionar un RUC válido');
        }

        if (request()->boolean('validate_exists', false)) {
            $exists = Company::query()
                ->where('ruc', $ruc)
                ->exists();

            if ($exists) {
                return $this->sendError('Este ruc ya esta registrado en el sistema');
            }
        }

        $token = env('CONSULTA_RUC_API_TOKEN');
        $url = 'https://api.apis.net.pe/v2/sunat/ruc/full';

        try {
            $response = Http::withToken($token)
                ->get($url, [
                    'numero' => $ruc,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'exists' => false,
                    'ruc' => $data['numeroDocumento'] ?? $ruc,
                    'razonSocial' => $data['razonSocial'] ?? null,
                    'direccion' => $data['direccion'] ?? null,
                    'data' => $data,
                ]);
            }

            if ($response->status() === 404) {
                return $this->sendError('No se encontró información para el RUC proporcionado');
            }

            return $this->sendResponse([
                'status' => $response->status(),
                'body' => $response->body(),
            ], 'Enviando datos consultados');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
