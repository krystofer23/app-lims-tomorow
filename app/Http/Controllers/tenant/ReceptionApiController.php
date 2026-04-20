<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\ChainCustody;
use App\Models\tenant\OrderService;
use App\Models\tenant\Record;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceptionApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $company_id = $request->input('company_id');
            $application_id = $request->input('application_id');
            $order_id = $request->input('order_id');

            $data = ChainCustody::query()
                ->with([
                    'company',
                    'application',
                    'order',
                ])
                ->when($request->filled('company_id'), fn($q) => $q->where('company_id', $company_id))
                ->when($request->filled('application_id'), fn($q) => $q->where('application_id', $application_id))
                ->when($request->filled('order_id'), fn($q) => $q->where('order_id', $order_id))
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando registros');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->all();
            $userId = Auth::guard('api')->id();

            $os = $input['order_id'] ? OrderService::select('id', 'code')->find($input['order_id'])?->code : null;

            $chainCustody = ChainCustody::create([
                'company_id' => $input['company_id'],
                'application_id' => $input['application_id'],
                'order_id' => $input['order_id'],
                'os' => $os,
                'content' => $input,
            ]);

            Record::create([
                'user_id' => $userId,
                'type' => 'created',
                'fileable_type' => ChainCustody::class,
                'fileable_id' => $chainCustody?->id,
            ]);

            DB::commit();
            return $this->sendSuccess('Registro creado con exito');
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
            $userId = Auth::guard('api')->id();

            $chainCustody = ChainCustody::find($id);

            if (!$chainCustody) {
                return $this->sendError('No se encontro el registro');
            }

            $os = $input['order_id'] ? OrderService::select('id', 'code')->find($input['order_id'])?->code : null;

            $chainCustody->update([
                'company_id' => $input['company_id'],
                'application_id' => $input['application_id'],
                'order_id' => $input['order_id'],
                'os' => $os,
                'content' => $input,
            ]);

            Record::create([
                'user_id' => $userId,
                'type' => 'updated',
                'fileable_type' => ChainCustody::class,
                'fileable_id' => $chainCustody?->id,
            ]);

            DB::commit();
            return $this->sendSuccess('Registro actualizado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $userId = Auth::guard('api')->id();

            $chainCustody = ChainCustody::find($id);

            if (!$chainCustody) {
                return $this->sendError('No se encontro el registro');
            }

            Record::create([
                'user_id' => $userId,
                'type' => 'deleted',
                'fileable_type' => ChainCustody::class,
                'fileable_id' => $chainCustody?->id,
            ]);

            $chainCustody->delete();

            DB::commit();
            return $this->sendSuccess('Registro eliminado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
