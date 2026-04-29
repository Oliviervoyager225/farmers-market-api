<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->foreignId('farmer_id')->constrained('farmers');
            $table->decimal('original_amount_fcfa', 15, 2);
            $table->decimal('remaining_amount_fcfa', 15, 2);
            $table->enum('status', ['open', 'partial', 'closed'])->default('open');
            $table->timestamps();

            $table->index(['farmer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
