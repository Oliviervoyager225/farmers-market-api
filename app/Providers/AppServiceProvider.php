<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Auth\Repositories\UserRepository;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Category\Repositories\CategoryRepository;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Debt\Repositories\DebtRepository;
use App\Domain\Debt\Repositories\DebtRepositoryInterface;
use App\Domain\Farmer\Repositories\FarmerRepository;
use App\Domain\Farmer\Repositories\FarmerRepositoryInterface;
use App\Domain\Product\Repositories\ProductRepository;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Repayment\Repositories\RepaymentRepository;
use App\Domain\Repayment\Repositories\RepaymentRepositoryInterface;
use App\Domain\Setting\Repositories\SettingRepository;
use App\Domain\Setting\Repositories\SettingRepositoryInterface;
use App\Domain\Transaction\Repositories\TransactionRepository;
use App\Domain\Transaction\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(FarmerRepositoryInterface::class, FarmerRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(DebtRepositoryInterface::class, DebtRepository::class);
        $this->app->bind(RepaymentRepositoryInterface::class, RepaymentRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
    }

    public function boot(): void {}
}
