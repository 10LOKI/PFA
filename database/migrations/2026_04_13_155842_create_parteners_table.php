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
        Schema::create('parteners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete(); // linked user account
            $table->string('company_name');
            $table->string('logo')->nullable();
            $table->text('bio')->nullable();                  // brand showcase page
            $table->string('website')->nullable();
            $table->string('sector')->nullable();             // NGO, corporate, public, etc.
            $table->string('rc_number')->nullable();          // KYC: registre de commerce
            $table->string('rc_document')->nullable();        // KYC: uploaded file path
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_certified')->default(false);  // auto-approves event publishing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parteners');
    }
};
