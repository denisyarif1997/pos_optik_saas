<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ReturnModel;
use App\Models\Sale;
use App\Models\StockLensMatrix;
use App\Policies\CategoryPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\ProductPolicy;
use App\Policies\PurchasePolicy;
use App\Policies\ReturnPolicy;
use App\Policies\SalePolicy;
use App\Policies\StockLensMatrixPolicy;
use App\Services\ActiveStore;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('activeStore', fn () => new ActiveStore);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Sale::class, SalePolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Purchase::class, PurchasePolicy::class);
        Gate::policy(ReturnModel::class, ReturnPolicy::class);
        Gate::policy(StockLensMatrix::class, StockLensMatrixPolicy::class);
        Gate::policy(Expense::class, ExpensePolicy::class);
    }
}
