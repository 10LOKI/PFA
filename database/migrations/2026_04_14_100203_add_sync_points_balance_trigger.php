<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER sync_points_balance_after_insert
            AFTER INSERT ON points_transactions
            FOR EACH ROW
            BEGIN
                UPDATE users 
                SET points_balance = (
                    SELECT COALESCE(SUM(
                        CASE 
                            WHEN type IN ("earned", "adjusted") AND amount > 0 THEN amount
                            WHEN type IN ("burned", "spent") AND amount > 0 THEN -amount
                            WHEN type = "adjusted" AND amount < 0 THEN amount
                            ELSE 0
                        END
                    ), 0)
                    FROM points_transactions
                    WHERE user_id = NEW.user_id
                )
                WHERE id = NEW.user_id;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER sync_points_balance_after_delete
            AFTER DELETE ON points_transactions
            FOR EACH ROW
            BEGIN
                UPDATE users 
                SET points_balance = (
                    SELECT COALESCE(SUM(
                        CASE 
                            WHEN type IN ("earned", "adjusted") AND amount > 0 THEN amount
                            WHEN type IN ("burned", "spent") AND amount > 0 THEN -amount
                            WHEN type = "adjusted" AND amount < 0 THEN amount
                            ELSE 0
                        END
                    ), 0)
                    FROM points_transactions
                    WHERE user_id = OLD.user_id
                )
                WHERE id = OLD.user_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS sync_points_balance_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS sync_points_balance_after_delete');
    }
};
