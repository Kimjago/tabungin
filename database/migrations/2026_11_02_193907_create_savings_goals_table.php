<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->decimal('saved_amount', 15, 2)->default(0);
            $table->date('deadline')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->string('color', 7)->default('#10b981');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_goals');
    }
};
