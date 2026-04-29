<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repayment_debt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repayment_id')->constrained('repayments')->cascadeOnDelete();
            $table->foreignId('debt_id')->constrained('debts');
            $table->decimal('amount_applied_fcfa', 15, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repayment_debt');
    }
};
