<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drops unused columns from users, partners, and notifications tables.
     * - users: avatar, phone, is_certified_partner
     * - partners: rc_number, rc_document
     * - notifications: event_id
     */
    public function up(): void
    {
        // Drop unused columns from users table
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = ['avatar', 'phone', 'is_certified_partner'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Drop unused columns from partners table
        Schema::table('partners', function (Blueprint $table) {
            $columnsToDrop = ['rc_number', 'rc_document'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('partners', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Drop event_id from notifications table
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'event_id')) {
                $table->dropColumn('event_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Restores dropped columns (for rollback).
     */
    public function down(): void
    {
        // Restore users table columns that were dropped
        Schema::table('users', function (Blueprint $table) {
            // Add avatar after role (original position)
            $table->string('avatar')->nullable()->after('role');
            // Add phone after city (original position)
            $table->string('phone')->nullable()->after('city');
            // Add is_certified_partner after kyc_verified (original position)
            $table->boolean('is_certified_partner')->default(false)->after('kyc_verified');
        });

        // Restore partners table columns
        Schema::table('partners', function (Blueprint $table) {
            $table->string('rc_number')->nullable()->after('sector');
            $table->string('rc_document')->nullable()->after('rc_number');
        });

        // Restore event_id to notifications table
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('event_id')->nullable()->after('link');
        });
    }
};
