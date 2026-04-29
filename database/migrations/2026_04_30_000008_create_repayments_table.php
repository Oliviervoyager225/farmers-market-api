<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('farmers');
            $table->foreignId('operator_id')->constrained('users');
            $table->decimal('kg_received', 10, 3);
            $table->decimal('commodity_rate_fcfa', 15, 2);
            $table->decimal('total_fcfa_credited', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
