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
         * 1. Agregar result_type si aún no existe.
         */
        if (!Schema::hasColumn($this->tableName, 'result_type')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->string('result_type', 20)
                    ->nullable()
                    ->after('result_axis');
            });
        }

        /**
         * 2. Crear índice normal de soporte para foreign keys.
         * Esto evita el error de MySQL cuando intentas borrar un índice usado por FK.
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
         * 3. Borrar índice único anterior:
         * order_id + item_id + chain_custody_id + result_axis
         */
        if ($this->indexExists('lab_results_order_item_chain_axis_unique')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->dropUnique('lab_results_order_item_chain_axis_unique');
            });
        }

        /**
         * 4. Completar registros antiguos.
         */
        DB::table($this->tableName)
            ->whereNull('result_axis')
            ->update([
                'result_axis' => 'NORMAL',
            ]);

        DB::table($this->tableName)
            ->whereNull('result_type')
            ->update([
                'result_type' => 'NORMAL',
            ]);

        /**
         * 5. Dejar result_axis y result_type como NOT NULL.
         */
        DB::statement("
            ALTER TABLE {$this->tableName}
            MODIFY result_axis VARCHAR(20) NOT NULL DEFAULT 'NORMAL'
        ");

        DB::statement("
            ALTER TABLE {$this->tableName}
            MODIFY result_type VARCHAR(20) NOT NULL DEFAULT 'NORMAL'
        ");

        /**
         * 6. Crear nuevo índice único completo.
         *
         * Ahora permite:
         * X + PPV
         * X + FREC
         * Y + PPV
         * Y + FREC
         * Z + PPV
         * Z + FREC
         */
        if (!$this->indexExists('lab_results_order_item_chain_axis_type_unique')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->unique(
                    ['order_id', 'item_id', 'chain_custody_id', 'result_axis', 'result_type'],
                    'lab_results_order_item_chain_axis_type_unique'
                );
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('lab_results_order_item_chain_axis_type_unique')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->dropUnique('lab_results_order_item_chain_axis_type_unique');
            });
        }

        if (!$this->indexExists('lab_results_order_item_chain_axis_unique')) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->unique(
                    ['order_id', 'item_id', 'chain_custody_id', 'result_axis'],
                    'lab_results_order_item_chain_axis_unique'
                );
            });
        }
    }
};
