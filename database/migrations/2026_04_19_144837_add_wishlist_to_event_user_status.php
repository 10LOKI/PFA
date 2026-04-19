<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE event_user MODIFY COLUMN status ENUM('registered', 'checked_in', 'absent', 'cancelled', 'wishlist') DEFAULT 'registered'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE event_user MODIFY COLUMN status ENUM('registered', 'checked_in', 'absent', 'cancelled') DEFAULT 'registered'");
    }
};
