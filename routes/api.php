<?php

declare(strict_types=1);

use App\Domain\Auth\Controllers\AuthController;
use App\Domain\Auth\Controllers\UserController;
use App\Domain\Category\Controllers\CategoryController;
use App\Domain\Debt\Controllers\DebtController;
use App\Domain\Farmer\Controllers\FarmerController;
use App\Domain\Notification\Controllers\NotificationController;
use App\Domain\Product\Controllers\ProductController;
use App\Domain\Repayment\Controllers\RepaymentController;
use App\Domain\Setting\Controllers\SettingController;
use App\Domain\Transaction\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Auth publique ──────────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
    });

    // ── Catalogue public (lecture seule) ──────────────────────────────────────
    Route::get('products',              [ProductController::class, 'index']);
    Route::get('products/{product}',    [ProductController::class, 'show']);
    Route::get('categories',            [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    // ── Routes protégées (utilisateur authentifié) ────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth — tous les rôles
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',      [AuthController::class, 'me']);

        // ── Admin & Supervisor ─────────────────────────────────────────────────

        Route::middleware('role:admin,supervisor')->group(function () {
            // Gestion des comptes utilisateurs
            Route::get('users',           [UserController::class, 'index']);
            Route::get('users/{user}',    [UserController::class, 'show']);
            Route::post('users',          [UserController::class, 'store']);
            Route::put('users/{user}',    [UserController::class, 'update']);
            Route::delete('users/{user}', [UserController::class, 'destroy']);

            // Gestion catalogue produits & catégories
            Route::post('products',              [ProductController::class, 'store']);
            Route::put('products/{product}',     [ProductController::class, 'update']);
            Route::delete('products/{product}',  [ProductController::class, 'destroy']);

            Route::post('categories',              [CategoryController::class, 'store']);
            Route::put('categories/{category}',    [CategoryController::class, 'update']);
            Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
        });

        // ── Admin uniquement ───────────────────────────────────────────────────

        Route::middleware('role:admin')->group(function () {
            Route::put('settings',       [SettingController::class, 'bulkUpdate']);
            Route::put('settings/{key}', [SettingController::class, 'update']);
        });

        // ── Tous les rôles authentifiés ────────────────────────────────────────

        // Paramètres (lecture — opérateurs ont besoin du taux kg et du taux d'intérêt)
        Route::get('settings', [SettingController::class, 'index']);

        // Agriculteurs
        Route::get('farmers',             [FarmerController::class, 'index']);
        Route::get('farmers/{farmer}',    [FarmerController::class, 'show']);
        Route::post('farmers',            [FarmerController::class, 'store']);
        Route::put('farmers/{farmer}',    [FarmerController::class, 'update']);
        Route::delete('farmers/{farmer}', [FarmerController::class, 'destroy']);

        // Dettes — résumé par agriculteur
        Route::get('farmers/{farmer}/debts',       [DebtController::class, 'byFarmer']);
        Route::get('farmers/{farmer}/repayments',  [RepaymentController::class, 'byFarmer']);
        Route::get('debts/{debt}',                 [DebtController::class, 'show']);

        // Transactions (ventes cash / crédit)
        Route::get('transactions',               [TransactionController::class, 'index']);
        Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
        Route::post('transactions',              [TransactionController::class, 'store']);

        // Remboursements en marchandise
        Route::get('repayments',             [RepaymentController::class, 'index']);
        Route::get('repayments/{repayment}', [RepaymentController::class, 'show']);
        Route::post('repayments',            [RepaymentController::class, 'store']);

        // Notifications (alertes dettes)
        Route::get('notifications',               [NotificationController::class, 'index']);
        Route::get('notifications/unread-count',  [NotificationController::class, 'unreadCount']);
        Route::put('notifications/read-all',      [NotificationController::class, 'markAllAsRead']);
        Route::put('notifications/{key}/read',    [NotificationController::class, 'markAsRead'])
            ->where('key', '.*');
    });
});
