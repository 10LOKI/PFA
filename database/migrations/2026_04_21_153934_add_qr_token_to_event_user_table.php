<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->string('qr_token')->unique()->nullable()->after('id');
        });

        // Backfill existing registrations with unique tokens
        $registrations = DB::table('event_user')->whereNull('qr_token')->get();
        foreach ($registrations as $reg) {
            DB::table('event_user')
                ->where('id', $reg->id)
                ->update(['qr_token' => Str::uuid()->toString()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};
