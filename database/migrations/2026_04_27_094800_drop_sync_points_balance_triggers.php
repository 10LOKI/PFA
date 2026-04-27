<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old sync_points_balance triggers — balance is now updated directly in CreditPointsAction
        DB::unprepared('DROP TRIGGER IF EXISTS sync_points_balance_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS sync_points_balance_after_delete');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left blank — full re-creation would require redefining the original trigger logic
        // If needed, the original trigger migration can be re-run
    }
};
