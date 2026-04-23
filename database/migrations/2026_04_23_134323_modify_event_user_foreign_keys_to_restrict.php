<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Changes foreign keys to RESTRICT on delete to preserve historical event_user records.
     * This ensures participation history cannot be lost by deleting a user or event.
     */
    public function up(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            // Temporarily disable foreign key checks (MySQL)
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Drop existing foreign keys
            $table->dropForeign(['event_id']);
            $table->dropForeign(['user_id']);

            // Re-add with RESTRICT ON DELETE (prevents deletion if registrations exist)
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Restores original CASCADE ON DELETE behavior.
     * WARNING: This may allow deletion of users/events with registrations.
     */
    public function down(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $table->dropForeign(['event_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }
};
