<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drops unused pivot columns from event_user table:
     * - partner_rating
     * - partner_feedback
     */
    public function up(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            if (Schema::hasColumn('event_user', 'partner_rating')) {
                $table->dropColumn('partner_rating');
            }
            if (Schema::hasColumn('event_user', 'partner_feedback')) {
                $table->dropColumn('partner_feedback');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Restores the columns (for rollback).
     */
    public function down(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->tinyInteger('partner_rating')->unsigned()->nullable()->after('points_earned');
            $table->text('partner_feedback')->nullable()->after('partner_rating');
        });
    }
};
