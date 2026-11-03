<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_goal_id')
                ->constrained()
                ->cascadeOnDelete();
            // transaksi sumber dana; opsional kalau setoran dari luar catatan
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('note', 255)->nullable();
            $table->date('date');
            $table->timestamps();

            $table->index(['savings_goal_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_contributions');
    }
};
