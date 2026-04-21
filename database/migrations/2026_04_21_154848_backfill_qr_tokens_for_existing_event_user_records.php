<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fetch all registrations without a QR token
        DB::table('event_user')
            ->whereNull('qr_token')
            ->orderBy('id')
            ->chunk(100, function ($registrations) {
                foreach ($registrations as $reg) {
                    DB::table('event_user')
                        ->where('id', $reg->id)
                        ->update(['qr_token' => Str::uuid()->toString()]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: clear tokens on rollback
        DB::table('event_user')->update(['qr_token' => null]);
    }
};
