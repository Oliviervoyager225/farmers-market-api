<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('farmers');
            $table->foreignId('operator_id')->constrained('users');
            $table->decimal('total_fcfa', 15, 2);
            $table->enum('payment_method', ['cash', 'credit']);
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal('interest_amount_fcfa', 15, 2)->nullable();
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->timestamps();

            $table->index(['farmer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
