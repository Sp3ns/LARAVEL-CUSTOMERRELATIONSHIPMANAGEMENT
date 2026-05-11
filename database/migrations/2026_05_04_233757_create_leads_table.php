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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable();
            $table->enum('status', [
                'New', 'Contacted', 'Qualified',
                'Proposal Sent', 'Negotiation', 'Won', 'Lost'
            ])->default('New');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->decimal('expected_value', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
