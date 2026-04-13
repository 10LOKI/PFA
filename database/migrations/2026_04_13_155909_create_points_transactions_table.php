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
        Schema::create('points_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['earned', 'spent', 'burned', 'adjusted']);
            $table->integer('amount');                        // positive = credit, negative = debit
            $table->unsignedInteger('balance_after');         // snapshot for transparent wallet
            $table->string('source_type')->nullable();        // morphable: event, reward_redemption, admin
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('description')->nullable();        // human-readable log entry
            $table->timestamps();

            $table->index(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_transactions');
    }
};
