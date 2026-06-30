<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $tableName = 'laboratory_results';

    private function indexExists(string $indexName): bool
    {
        $indexes = DB::select("
            SHOW INDEX FROM {$this->tableName}
            WHERE Key_name = ?
        ", [$indexName]);

        return count($indexes) > 0;
    }

    public function up(): void
    {
        /**
         * 1. Agregar result_axis si aún no existe.
         * Si la migración anterior falló, puede que esta columna ya se haya creado.
         */
        if (!Schema::hasColumn($this->tableName, 'result_axis')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->string('result_axis', 20)
                    ->nullable()
                    ->after('result');
            });
        }

        /**
         * 2. Crear un índice normal de soporte.
         *
         * Esto es importante porque el índice único antiguo está siendo usado
         * por una foreign key. Si no creamos este índice antes, MySQL no deja
         * eliminar el unique anterior.
         */
        if (!$this->indexExists('lab_results_order_item_chain_fk_idx')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index(
                    ['order_id', 'item_id', 'chain_custody_id'],
                    'lab_results_order_item_chain_fk_idx'
                );
            });
        }

        /**
         * 3. Eliminar índice único antiguo.
         */
        if ($this->indexExists('lab_results_order_item_chain_unique')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->dropUnique('lab_results_order_item_chain_unique');
            });
        }

        /**
         * 4. Actualizar registros antiguos.
         */
        DB::table($this->tableName)
            ->whereNull('result_axis')
            ->update([
                'result_axis' => 'NORMAL',
            ]);

        /**
         * 5. Dejar result_axis como NOT NULL con default NORMAL.
         */
        DB::statement("
            ALTER TABLE {$this->tableName}
            MODIFY result_axis VARCHAR(20) NOT NULL DEFAULT 'NORMAL'
        ");

        /**
         * 6. Crear el nuevo índice único incluyendo result_axis.
         *
         * Ahora sí permitirá:
         * order_id + item_id + chain_custody_id + NORMAL
         * order_id + item_id + chain_custody_id + X
         * order_id + item_id + chain_custody_id + Y
         * order_id + item_id + chain_custody_id + Z
         */
        if (!$this->indexExists('lab_results_order_item_chain_axis_unique')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->unique(
                    ['order_id', 'item_id', 'chain_custody_id', 'result_axis'],
                    'lab_results_order_item_chain_axis_unique'
                );
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('lab_results_order_item_chain_axis_unique')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->dropUnique('lab_results_order_item_chain_axis_unique');
            });
        }

        /**
         * Ojo:
         * Este down puede fallar si ya tienes registros X, Y, Z para el mismo
         * order_id + item_id + chain_custody_id.
         */
        if (!$this->indexExists('lab_results_order_item_chain_unique')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->unique(
                    ['order_id', 'item_id', 'chain_custody_id'],
                    'lab_results_order_item_chain_unique'
                );
            });
        }
    }
};
