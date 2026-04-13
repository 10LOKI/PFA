<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['event_id', 'user_id']);          // no double registration
            $table->enum('status', ['registered', 'checked_in', 'absent', 'cancelled'])->default('registered');
            $table->timestamp('checked_in_at')->nullable();   // set on QR scan
            $table->unsignedInteger('points_earned')->default(0);
            $table->tinyInteger('partner_rating')->unsigned()->nullable(); // partner rates student 1-5
            $table->text('partner_feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_user');
    }
};
