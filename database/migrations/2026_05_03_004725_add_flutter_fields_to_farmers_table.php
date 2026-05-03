<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('farm_size', 10, 2)->nullable();
            $table->integer('experience')->nullable();
            $table->json('categories')->nullable();
            $table->json('specialties')->nullable();
            $table->string('certification')->nullable();
            $table->string('primary_market')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn([
                'email', 'state', 'city', 'address', 'bio', 'farm_size',
                'experience', 'categories', 'specialties', 'certification', 'primary_market'
            ]);
        });
    }
};
