<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Company;
use App\Models\tenant\Conditions;
use App\Models\tenant\ContactCompanies;
use App\Models\tenant\Essays;
use App\Models\tenant\Item;
use App\Models\tenant\Matrix;
use App\Models\tenant\Matriz;
use App\Models\tenant\Methodologies;
use App\Models\tenant\OrderService;
use App\Models\tenant\Parameters;
use App\Models\tenant\Services;
use App\Models\tenant\TypeOfAnalysis;
use App\Models\tenant\TypeOfSamples;
use App\Models\tenant\UnitsMeasurement;
use App\Models\Tenant\User;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ListApiController extends Controller
{
    public function ordersOptimizate(Request $request): JsonResponse
    {
        try {
            $search = trim((string) $request->input('search'));

            $data = OrderService::query()
                ->select([
                    'id',
                    'code',
                    'company_id',
                    'application_id',
                ])
                ->with([
                    'company:id,business_name',
                    'application:id,business_name',
                ])
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('code', 'like', "%{$search}%")
                            ->orWhereHas('company', function ($companyQuery) use ($search) {
                                $companyQuery->where('business_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('application', function ($applicationQuery) use ($search) {
                                $applicationQuery->where('business_name', 'like', "%{$search}%");
                            });
                    });
                })
                ->orderByDesc('id')
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando os');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

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
                    ->orWhere('business_name', 'like', "%$query%")
                    ->orWhere('id', $query);
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
                $q->where('description', 'like', "%$query%")
                    ->orWhere('id', $query);
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

    public function contacts(Request $request): JsonResponse
    {
        try {
            $query = $request->input('query', null);

            $data = ContactCompanies::query()
                ->with('user')
                ->where('active', true)
                ->where('company_id', $request->company_id)
                ->whereHas('user', function ($q) use ($query) {
                    $q->where('full_name', 'like', "%{$query}%");
                })
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando contactos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function teams(Request $request): JsonResponse
    {
        try {
            $url = rtrim(env('SYSTEM_B_URL'), '/') . '/api-tems';

            $filters = array_filter([
                'os' => $request->input('os'),
                'code' => $request->input('code'),
                'serie' => $request->input('serie'),
                'status' => $request->input('status'),
                'search' => $request->input('search'),
                'denomination' => $request->input('denomination'),
                'page' => $request->input('page'),
                'per_page' => $request->input('per_page', 15),
            ], fn($value) => !is_null($value) && $value !== '');

            $response = Http::withHeaders([
                'X-API-KEY' => env('INTERNAL_API_KEY'),
                'Accept' => 'application/json',
            ])->timeout(30)->post($url, $filters);

            if (! $response->successful()) {
                return $this->sendError([
                    'message' => 'La API externa devolvió un error',
                    'error' => $response->json() ?? $response->body(),
                ]);
            }

            return response()->json($response->json());
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function parameters(Request $request): JsonResponse
    {
        try {
            $type = $request->input('type');
            $matrix = $request->input('matrix');
            $product = $request->input('product');
            $condition = $request->input('condition');
            $typeOfAnalysis = $request->input('type_of_analysis');

            $orderId = $request->input('order_id');
            $parametersIds = collect();

            if ($orderId) {
                $order = OrderService::findOrFail($orderId);

                $parametersIds = $order->items()
                    ->where('type', 'matrix')
                    ->get()
                    ->pluck('item.parameter_id')
                    ->filter()
                    ->values();
            }

            $data = Item::query()
                ->with([
                    'typeOfSample',
                    'condition',
                    'matrix',
                    'reference',
                    'parameter',
                    'parameter.connectionsParameter' => fn($q) => $q
                        ->when($matrix, fn($query) => $query->where('matrix_id', $matrix))
                        ->when($product, fn($query) => $query->where('type_of_samples_id', $product)),
                    'parameter.connectionsParameter.matrix',
                    'unitMeasurement',
                    'company',
                ])
                ->when($condition, function ($query) use ($condition) {
                    $query->where('condition_id', $condition);
                })
                ->when($type, function ($query) use ($type) {
                    $query->where('type', $type);
                })
                ->when($product, function ($query) use ($product) {
                    $query->where(function ($query) use ($product) {
                        $query->where('type_of_sample_id', $product)
                            ->orWhereHas('parameter.connectionsParameter', function ($query) use ($product) {
                                $query->where('type_of_samples_id', $product);
                            });
                    });
                })
                ->when($matrix, function ($query) use ($matrix) {
                    $query->where(function ($query) use ($matrix) {
                        $query->where('matrix_id', $matrix)
                            ->orWhereHas('parameter.connectionsParameter', function ($query) use ($matrix) {
                                $query->where('matrix_id', $matrix);
                            });
                    });
                })
                ->when($typeOfAnalysis, function ($query) use ($typeOfAnalysis) {
                    $query->whereHas('parameter', function ($query) use ($typeOfAnalysis) {
                        $query->where('type_of_analysis_id', $typeOfAnalysis);
                    });
                })
                ->when($parametersIds->isNotEmpty(), function ($query) use ($parametersIds) {
                    $query->whereIn('parameter_id', $parametersIds->toArray());
                })
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando items');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function users(Request $request): JsonResponse
    {
        try {
            $search = $request->input('search');

            $data = User::query()
                ->when($request->filled('search'), function ($q) use ($search) {
                    $q->where(function ($query) use ($search) {
                        $query->where('full_name', 'LIKE', "%{$search}%")
                            ->orWhere('document_number', 'LIKE', "%{$search}%");
                    });
                })
                ->paginate(20);

            return $this->sendResponse($data, 'Enviando usuarios');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function types(): JsonResponse
    {
        try {
            $types = Item::query()
                ->distinct()
                ->orderBy('type')
                ->pluck('type')
                ->values();

            return $this->sendResponse($types, 'Enviando tipos únicos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function matrixs(): JsonResponse
    {
        try {
            $matrixs = Matrix::query()
                ->get();

            return $this->sendResponse($matrixs, 'Enviando matrices únicas');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function typesSampling(Request $request): JsonResponse
    {
        try {
            $data = TypeOfSamples::query()
                ->get();

            return $this->sendResponse($data, 'Enviando tipos de muestras');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function typesAnalysis(Request $request): JsonResponse
    {
        try {
            $matrix = $request->input('matrix');
            $product = $request->input('product');
            $condition = $request->input('condition');
            $search = $request->input('search');

            $parametersIds = Item::query()
                ->when($product, function ($query) use ($product) {
                    $query->where(function ($q) use ($product) {
                        $q->where('type_of_sample_id', $product)
                            ->orWhereHas('parameter.connectionsParameter', function ($subQuery) use ($product) {
                                $subQuery->where('type_of_samples_id', $product);
                            });
                    });
                })
                ->when($matrix, function ($query) use ($matrix) {
                    $query->where(function ($q) use ($matrix) {
                        $q->where('matrix_id', $matrix)
                            ->orWhereHas('parameter.connectionsParameter', function ($subQuery) use ($matrix) {
                                $subQuery->where('matrix_id', $matrix);
                            });
                    });
                })
                ->when($condition, function ($q) use ($condition) {
                    $q->where('condition_id', $condition);
                })
                ->pluck('parameter_id')
                ->filter()
                ->unique()
                ->values();

            $typeOfAnalysisIds = Parameters::query()
                ->whereIn('id', $parametersIds)
                ->pluck('type_of_analysis_id')
                ->filter()
                ->unique()
                ->values();

            $data = TypeOfAnalysis::query()
                ->when($search, fn($q) => $q->where('description', 'like', "%$search%"))
                ->when($typeOfAnalysisIds->isNotEmpty(), function ($q) use ($typeOfAnalysisIds) {
                    $q->whereIn('id', $typeOfAnalysisIds);
                })
                ->get();

            return $this->sendResponse($data, 'Enviando tipos de análisis');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function typesItems(Request $request): JsonResponse
    {
        try {
            $data = Item::query()
                ->whereNotNull('type')
                ->distinct()
                ->orderBy('type')
                ->pluck('type');

            return $this->sendResponse($data, 'Enviando tipos de datos únicos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
