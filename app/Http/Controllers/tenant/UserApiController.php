<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->input('q');

            $data = User::query()
                ->when($request->filled('q'), function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%")
                            ->orWhere('document_number', 'like', "%{$search}%");
                    });
                })
                ->with('roles')
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando usuarios');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            $user = User::create([
                'name' => $input['name'],
                'last_name_first' => $input['last_name_first'],
                'last_name_second' => $input['last_name_second'],
                'full_name' => $input['name'] . ' ' . $input['last_name_first'] . ' ' . $input['last_name_second'],
                'type_document' => $input['type_document'],
                'document_number' => $input['document_number'],
                'sex' => $input['sex'],
                'age' => $input['age'],
                'birthdate' => $input['birthdate'],
                'email' => $input['email'],
                'password' => $input['password'],
                'active' => $input['active'],
            ]);

            $user->assignRole($input['role']);

            DB::commit();
            return $this->sendSuccess('Usuario creado con exito');
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

            $user = User::find($id);
            $user->update([
                'name' => $input['name'],
                'last_name_first' => $input['last_name_first'],
                'last_name_second' => $input['last_name_second'],
                'full_name' => $input['name'] . ' ' . $input['last_name_first'] . ' ' . $input['last_name_second'],
                'type_document' => $input['type_document'],
                'document_number' => $input['document_number'],
                'sex' => $input['sex'],
                'age' => $input['age'],
                'birthdate' => $input['birthdate'],
                'email' => $input['email'],
                'active' => $input['active'],
            ]);

            if ($request->password && $input['password']) {
                $user->update([
                    'password' => $input['password'],
                ]);
            }

            $user->syncRoles([]);
            $user->assignRole($input['role']);

            DB::commit();
            return $this->sendSuccess('Usuario actualizado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);
            $user->syncRoles([]);
            $user->delete();

            DB::commit();
            return $this->sendSuccess('Usuario eliminado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
