<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Auth\Enums\UserRole;
use App\Models\Category;
use App\Models\Farmer;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Utilisateurs ───────────────────────────────────────────────────

        $admin = User::firstOrCreate(['email' => 'admin@farmmarket.ci'], [
            'name'     => 'Super Admin',
            'password' => Hash::make('password'),
            'role'     => UserRole::Admin->value,
        ]);

        $supervisor = User::firstOrCreate(['email' => 'supervisor@farmmarket.ci'], [
            'name'        => 'Chef de Zone',
            'password'    => Hash::make('password'),
            'role'        => UserRole::Supervisor->value,
            'supervisor_id' => $admin->id,
        ]);

        $operator = User::firstOrCreate(['email' => 'operator@farmmarket.ci'], [
            'name'        => 'Opérateur POS Abidjan',
            'password'    => Hash::make('password'),
            'role'        => UserRole::Operator->value,
            'supervisor_id' => $supervisor->id,
        ]);

        // ── 2. Paramètres système ─────────────────────────────────────────────

        Setting::firstOrCreate(['key' => 'kg_to_cfa_rate'],        ['value' => '1000']);
        Setting::firstOrCreate(['key' => 'default_interest_rate'], ['value' => '0.30']);

        // ── 3. Catégories (intrants agricoles) ────────────────────────────────

        // Racines
        $engrais = Category::firstOrCreate(['name' => 'Engrais'], [
            'icon'        => '🌱',
            'description' => 'Produits fertilisants pour nourrir les plantes et améliorer les rendements.',
        ]);

        $phyto = Category::firstOrCreate(['name' => 'Phytosanitaires'], [
            'icon'        => '🛡️',
            'description' => 'Produits de protection des cultures contre maladies, insectes et mauvaises herbes.',
        ]);

        $amendements = Category::firstOrCreate(['name' => 'Amendements'], [
            'icon'        => '🪨',
            'description' => 'Produits d\'amélioration du sol (structure, pH, rétention d\'eau).',
        ]);

        $semences = Category::firstOrCreate(['name' => 'Semences & Plants'], [
            'icon'        => '🌾',
            'description' => 'Semences hybrides et plants améliorés à haute productivité.',
        ]);

        $biostimulants = Category::firstOrCreate(['name' => 'Biostimulants'], [
            'icon'        => '⚗️',
            'description' => 'Produits naturels pour stimuler la croissance et l\'absorption des nutriments.',
        ]);

        // Sous-catégories — Engrais
        $engraisMin = Category::firstOrCreate(['name' => 'Engrais minéraux'], [
            'icon'        => '🧪',
            'description' => 'Engrais de synthèse chimique à haute concentration.',
            'parent_id'   => $engrais->id,
        ]);

        $engraisOrg = Category::firstOrCreate(['name' => 'Engrais organiques'], [
            'icon'        => '♻️',
            'description' => 'Engrais naturels issus de matières organiques.',
            'parent_id'   => $engrais->id,
        ]);

        // Sous-catégories — Phytosanitaires
        $insecticides = Category::firstOrCreate(['name' => 'Insecticides'], [
            'icon'        => '🐛',
            'description' => 'Produits pour lutter contre les insectes ravageurs.',
            'parent_id'   => $phyto->id,
        ]);

        $herbicides = Category::firstOrCreate(['name' => 'Herbicides'], [
            'icon'        => '🌿',
            'description' => 'Produits de destruction des mauvaises herbes.',
            'parent_id'   => $phyto->id,
        ]);

        $fongicides = Category::firstOrCreate(['name' => 'Fongicides'], [
            'icon'        => '🍄',
            'description' => 'Produits anti-fongiques pour lutter contre les champignons.',
            'parent_id'   => $phyto->id,
        ]);

        // ── 4. Produits (intrants vendus en Côte d'Ivoire) ───────────────────

        // Engrais minéraux
        Product::firstOrCreate(['name' => 'NPK 15-15-15 (50 kg)'], [
            'category_id' => $engraisMin->id,
            'description' => 'Engrais complet équilibré (azote, phosphore, potassium). Convient à la plupart des cultures : cacao, café, maïs.',
            'price_fcfa'  => 25000,
        ]);

        Product::firstOrCreate(['name' => 'Urée 46% N (50 kg)'], [
            'category_id' => $engraisMin->id,
            'description' => 'Engrais azoté concentré. Stimule la croissance végétative. Idéal pour le maïs et le riz.',
            'price_fcfa'  => 18000,
        ]);

        Product::firstOrCreate(['name' => 'DAP 18-46-0 (50 kg)'], [
            'category_id' => $engraisMin->id,
            'description' => 'Phosphate diammonique. Idéal en fond de plantation pour le démarrage des cultures.',
            'price_fcfa'  => 22000,
        ]);

        // Engrais organiques
        Product::firstOrCreate(['name' => 'Compost enrichi (25 kg)'], [
            'category_id' => $engraisOrg->id,
            'description' => 'Compost stabilisé enrichi en humus. Améliore la structure et la fertilité du sol.',
            'price_fcfa'  => 5000,
        ]);

        Product::firstOrCreate(['name' => 'Fumier granulé (40 kg)'], [
            'category_id' => $engraisOrg->id,
            'description' => 'Fumier de volaille transformé en granulés. Libération lente des nutriments.',
            'price_fcfa'  => 7500,
        ]);

        // Insecticides
        Product::firstOrCreate(['name' => 'Cyperméthrine 10 EC (1 L)'], [
            'category_id' => $insecticides->id,
            'description' => 'Insecticide pyréthrinoïde de contact et d\'ingestion. Efficace contre chenilles et pucerons.',
            'price_fcfa'  => 8500,
        ]);

        Product::firstOrCreate(['name' => 'Chlorpyrifos 48 EC (1 L)'], [
            'category_id' => $insecticides->id,
            'description' => 'Insecticide organophosphoré systémique. Lutte contre les mirides du cacao.',
            'price_fcfa'  => 11000,
        ]);

        // Herbicides
        Product::firstOrCreate(['name' => 'Glyphosate 360 SL (5 L)'], [
            'category_id' => $herbicides->id,
            'description' => 'Herbicide total systémique post-levée. Détruit les adventices annuelles et vivaces.',
            'price_fcfa'  => 12000,
        ]);

        Product::firstOrCreate(['name' => 'Atrazine 80 WP (1 kg)'], [
            'category_id' => $herbicides->id,
            'description' => 'Herbicide sélectif pré-levée pour cultures de maïs. Prévient la germination des graminées.',
            'price_fcfa'  => 6500,
        ]);

        // Fongicides
        Product::firstOrCreate(['name' => 'Mancozèbe 80 WP (1 kg)'], [
            'category_id' => $fongicides->id,
            'description' => 'Fongicide de contact multi-site. Protège contre les moisissures, mildiou et rouille.',
            'price_fcfa'  => 9500,
        ]);

        Product::firstOrCreate(['name' => 'Métalaxyl 35 WS (1 kg)'], [
            'category_id' => $fongicides->id,
            'description' => 'Fongicide systémique contre le mildiou et les pythiacées. Traitement semences.',
            'price_fcfa'  => 14000,
        ]);

        // Amendements
        Product::firstOrCreate(['name' => 'Chaux agricole (50 kg)'], [
            'category_id' => $amendements->id,
            'description' => 'Corrige l\'acidité du sol (remonte le pH). Indispensable avant plantation de cacao.',
            'price_fcfa'  => 6000,
        ]);

        Product::firstOrCreate(['name' => 'Biochar (20 kg)'], [
            'category_id' => $amendements->id,
            'description' => 'Charbon végétal actif. Améliore la rétention d\'eau et la vie microbienne du sol.',
            'price_fcfa'  => 9000,
        ]);

        // Semences
        Product::firstOrCreate(['name' => 'Semences hybrides maïs (5 kg)'], [
            'category_id' => $semences->id,
            'description' => 'Variété hybride haut rendement, résistante à la sécheresse. Cycle 90 jours.',
            'price_fcfa'  => 15000,
        ]);

        Product::firstOrCreate(['name' => 'Riz NERICA (25 kg)'], [
            'category_id' => $semences->id,
            'description' => 'Variété améliorée de riz pluvial à haut rendement. Tolérante à la sécheresse.',
            'price_fcfa'  => 12000,
        ]);

        Product::firstOrCreate(['name' => 'Plants de cacao hybrides (lot 50)'], [
            'category_id' => $semences->id,
            'description' => 'Plants issus de clones sélectionnés. Résistants aux maladies et productifs dès 2 ans.',
            'price_fcfa'  => 20000,
        ]);

        // Biostimulants
        Product::firstOrCreate(['name' => 'Extraits d\'algues liquide (1 L)'], [
            'category_id' => $biostimulants->id,
            'description' => 'Biostimulant naturel à base d\'algues marines. Stimule la floraison et la nouaison.',
            'price_fcfa'  => 22000,
        ]);

        Product::firstOrCreate(['name' => 'Acides humiques (1 kg)'], [
            'category_id' => $biostimulants->id,
            'description' => 'Améliore l\'absorption des nutriments et la structure racinaire. Compatible tous engrais.',
            'price_fcfa'  => 18000,
        ]);

        // ── 5. Agriculteurs réalistes (Côte d'Ivoire) ────────────────────────

        Farmer::firstOrCreate(['identifier' => 'AGR-CI-001'], [
            'firstname'         => 'Kouassi',
            'lastname'          => 'Amani',
            'phone'             => '+22507010001',
            'credit_limit_fcfa' => 150000,
            'operator_id'       => $operator->id,
        ]);

        Farmer::firstOrCreate(['identifier' => 'AGR-CI-002'], [
            'firstname'         => 'Adjoua',
            'lastname'          => 'Brou',
            'phone'             => '+22507010002',
            'credit_limit_fcfa' => 100000,
            'operator_id'       => $operator->id,
        ]);

        Farmer::firstOrCreate(['identifier' => 'AGR-CI-003'], [
            'firstname'         => 'Yao',
            'lastname'          => 'Konan',
            'phone'             => '+22507010003',
            'credit_limit_fcfa' => 200000,
            'operator_id'       => $operator->id,
        ]);

        Farmer::firstOrCreate(['identifier' => 'AGR-CI-004'], [
            'firstname'         => 'Ama',
            'lastname'          => 'Kouamé',
            'phone'             => '+22507010004',
            'credit_limit_fcfa' => 75000,
            'operator_id'       => $operator->id,
        ]);
    }
}
