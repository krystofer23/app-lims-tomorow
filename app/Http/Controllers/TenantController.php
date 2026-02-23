<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Models\Domain;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        $tenants = Tenant::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('id', 'like', "%{$q}%")
                    ->orWhere('data->name', 'like', "%{$q}%");
            })
            ->with('domains')
            ->latest()
            ->paginate((int) $request->query('per_page', 15));

        return response()->json($tenants);
    }

    public function store(Request $request): JsonResponse
    {
        // 1) base domain seguro (sin http, sin /, sin puntos al final)
        $baseDomain = (string) config('app.base_domain');
        $baseDomain = preg_replace('#^https?://#i', '', $baseDomain);
        $baseDomain = preg_replace('#/.*$#', '', $baseDomain);
        $baseDomain = trim($baseDomain);
        $baseDomain = trim($baseDomain, '.'); // <- clave

        if ($baseDomain === '') {
            return response()->json([
                'message' => 'APP_BASE_DOMAIN está vacío. Configura config(app.base_domain).'
            ], 500);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'tenant_id' => ['nullable', 'string', 'max:64'],
            'subdomain' => ['required', 'string', 'max:63', 'regex:/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/'],
        ]);

        // 2) subdomain seguro (sin puntos)
        $sub = Str::lower(trim($data['subdomain']));
        $sub = trim($sub, '.'); // <- evita "greenlab."

        // 3) fqdn completo
        $fqdn = "{$sub}.{$baseDomain}";

        // check extra por si algo raro
        if (!str_contains($fqdn, '.') || str_ends_with($fqdn, '.')) {
            return response()->json([
                'message' => "Dominio inválido generado: {$fqdn}. Revisa APP_BASE_DOMAIN."
            ], 422);
        }

        $reserved = ['www', 'central', 'app', 'admin', 'api'];
        if (in_array($sub, $reserved, true)) {
            return response()->json(['message' => 'Subdominio reservado.'], 422);
        }

        if (Domain::query()->where('domain', $fqdn)->exists()) {
            return response()->json(['message' => 'Ese subdominio ya existe.'], 422);
        }

        $tenantId = $data['tenant_id'] ?? (string) Str::uuid();
        $dbName = 'tenant_' . Str::slug($sub, '_');

        try {
            DB::beginTransaction();

            /** @var Tenant $tenant */
            $tenant = Tenant::create([
                'id' => $tenantId,
                // Si tu tabla no tiene esta columna, elimina esta línea:
                'tenancy_db_name' => $dbName,
                'data' => [
                    'name' => $data['name'],
                    'subdomain' => $sub,
                    'database' => $dbName, // común en setups de Stancl
                ],
            ]);

            // ✅ aquí ya se guarda el FQDN completo
            $tenant->domains()->create([
                'domain' => $fqdn,
            ]);

            DB::commit();

            // ✅ 1) Crear base de datos
            $charset = 'utf8mb4';
            $collation = 'utf8mb4_unicode_ci';
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET $charset COLLATE $collation");

            // ✅ 2) Migraciones tenant
            $tenant->run(function () {
                Artisan::call('migrate', [
                    '--force' => true,
                    // '--path' => 'database/migrations/tenant',
                ]);
            });

            return response()->json([
                'message' => 'Tenant creado, dominio guardado completo y BD lista.',
                'tenant' => $tenant->load('domains'),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error creando tenant.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Tenant $tenant): JsonResponse
    {
        try {
            // opcional: borrar domains primero
            $tenant->domains()->delete();

            // opcional: si quieres borrar DB tenant, ojo con producción
            // DB::statement('DROP DATABASE ' . $tenant->tenancy_db_name);

            $tenant->delete();

            return response()->json(['message' => 'Tenant eliminado.']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error eliminando tenant.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
