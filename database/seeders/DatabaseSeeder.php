<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Auth\Enums\UserRole;
use App\Models\Category;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name'     => 'Super Admin',
            'password' => Hash::make('password'),
            'role'     => UserRole::Admin->value,
        ]);

        // 2. Create Operator
        $operator = User::firstOrCreate([
            'email' => 'operator@example.com',
        ], [
            'name'     => 'Market Operator',
            'password' => Hash::make('password'),
            'role'     => UserRole::Operator->value,
        ]);

        // 3. Create Categories (Arborescence)
        $vegetables = Category::firstOrCreate(['name' => 'Légumes']);
        Category::firstOrCreate(['name' => 'Légumes Racines', 'parent_id' => $vegetables->id]);
        Category::firstOrCreate(['name' => 'Légumes Feuilles', 'parent_id' => $vegetables->id]);

        $fruits = Category::firstOrCreate(['name' => 'Fruits']);
        Category::firstOrCreate(['name' => 'Agrumes', 'parent_id' => $fruits->id]);
        Category::firstOrCreate(['name' => 'Fruits Rouges', 'parent_id' => $fruits->id]);

        // 4. Create Farmers
        $farmer1 = Farmer::firstOrCreate([
            'identifier' => 'FARM-001',
        ], [
            'firstname'         => 'Jean',
            'lastname'          => 'Dupont',
            'phone'             => '+33600000001',
            'credit_limit_fcfa' => 50000,
            'operator_id'       => $operator->id,
        ]);

        $farmer2 = Farmer::firstOrCreate([
            'identifier' => 'FARM-002',
        ], [
            'firstname'         => 'Marie',
            'lastname'          => 'Curie',
            'phone'             => '+33600000002',
            'credit_limit_fcfa' => 100000,
            'operator_id'       => $operator->id,
        ]);

        // 5. Create Products
        Product::firstOrCreate([
            'name' => 'Carottes bio',
        ], [
            'category_id' => Category::where('name', 'Légumes Racines')->first()->id,
            'description' => 'Carottes bio de la région.',
            'price_fcfa'  => 1500,
        ]);

        Product::firstOrCreate([
            'name' => 'Pommes Gala',
        ], [
            'category_id' => Category::where('name', 'Fruits')->first()->id,
            'description' => 'Pommes douces et sucrées.',
            'price_fcfa'  => 2000,
        ]);
    }
}
