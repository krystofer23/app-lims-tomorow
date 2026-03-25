<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Company;
use App\Models\tenant\ContactCompanies;
use App\Models\tenant\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompaniesApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = Company::query()
                ->with([
                    'contacts.user'
                ])
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando empresas');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        $data = Company::query()->with([
            'contacts.user',
        ])->findOrFail($id);


        return $this->sendResponse($data, 'Enviando empresa');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            $company = Company::create([
                'ruc' => $input['ruc'],
                'business_name' => $input['business_name'],
                'direction' => $input['direction'],
                'activity' => $input['activity'],
                'is_supplier' => $input['is_supplier'],
                'is_partner' => $input['is_partner'],
                'state' => $input['state']
            ]);

            $contacts = $request->input('contacts', []);

            if (is_array($contacts) && count($contacts) !== 0) {
                foreach ($contacts as $key => $contac) {
                    $user = User::create([
                        'full_name' => $contac['full_name'],
                    ]);

                    ContactCompanies::create([
                        'phone' => $contac['phone'],
                        'email' => $contac['email'],
                        'active' => true,
                        'company_id' => $company?->id,
                        'user_id' => $user?->id,
                        'is_collection' => $contac['is_collection'],
                        'type' => $contac['type']
                    ]);
                }
            }

            DB::commit();
            return $this->sendSuccess('Empresa creada con exito');
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

            $company = Company::findOrFail($id);
            $company->update([
                'ruc'          => $input['ruc'] ?? null,
                'business_name' => $input['business_name'] ?? null,
                'direction'    => $input['direction'] ?? null,
                'activity'     => $input['activity'] ?? null,
                'is_supplier'  => (bool) ($input['is_supplier'] ?? false),
                'is_partner'   => (bool) ($input['is_partner'] ?? false),
                'state' => $input['state']
            ]);

            $contacts = $request->input('contacts', []);

            if (is_array($contacts) && count($contacts) > 0) {
                foreach ($contacts as $contac) {
                    $contactId = $contac['id'] ?? null;

                    if ($contactId) {
                        $c = ContactCompanies::with('user')->find($contactId);

                        if (! $c) {
                            continue;
                        }

                        $c->update([
                            'phone'         => $contac['phone'] ?? null,
                            'email'         => $contac['email'] ?? null,
                            'active'        => (bool) ($contac['active'] ?? true),
                            'is_collection' => (bool) ($contac['is_collection'] ?? false),
                            'type'          => $contac['type'] ?? null,
                        ]);

                        if ($c->user && !empty($contac['full_name'])) {
                            $c->user->update([
                                'full_name' => $contac['full_name'],
                            ]);
                        }
                    } else {
                        $user = User::create([
                            'full_name' => $contac['full_name'] ?? '',
                        ]);

                        ContactCompanies::create([
                            'phone'         => $contac['phone'] ?? null,
                            'email'         => $contac['email'] ?? null,
                            'active'        => true,
                            'company_id'    => $company->id,
                            'user_id'       => $user->id,
                            'is_collection' => (bool) ($contac['is_collection'] ?? false),
                            'type'          => $contac['type'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return $this->sendSuccess('Empresa editada con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
