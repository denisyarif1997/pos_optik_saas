<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\PrintController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Billing\Index as BillingIndex;
use App\Livewire\Categories\Form;
use App\Livewire\Categories\Index as CategoryIndex;
use App\Livewire\Customers\Form as CustomerForm;
use App\Livewire\Customers\Index as CustomerIndex;
use App\Livewire\Customers\Show as CustomerShow;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Expenses\Index as ExpenseIndex;
use App\Livewire\Inventory\Index as InventoryIndex;
use App\Livewire\Inventory\LensGrid;
use App\Livewire\Lab\LabOrderTracker;
use App\Livewire\Lab\QcChecklist;
use App\Livewire\OpticalOrders\Form as OpticalOrderForm;
use App\Livewire\OpticalOrders\Index as OpticalOrderIndex;
use App\Livewire\OpticalOrders\Receivables as OpticalOrderReceivables;
use App\Livewire\OpticalOrders\Show as OpticalOrderShow;
use App\Livewire\Pos\Cashier;
use App\Livewire\Prescriptions\Form as PrescriptionForm;
use App\Livewire\Products\Adjust as ProductAdjust;
use App\Livewire\Products\Form as ProductForm;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Purchases\Index as PurchaseIndex;
use App\Livewire\Reports\Index as ReportIndex;
use App\Livewire\Returns\Index as ReturnIndex;
use App\Livewire\Sales\History as SaleHistory;
use App\Livewire\Sales\Show as SaleShow;
use App\Livewire\Settings\Staff as StaffSettings;
use App\Livewire\Settings\StoreSettings;
use App\Livewire\StockOpnames\Count;
use App\Livewire\StockOpnames\Index;
use App\Livewire\Stores\Create as StoreCreate;
use App\Livewire\Suppliers\Index as SupplierIndex;
use App\Livewire\Warranties\Index as WarrantyIndex;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    // Profil tokomu sendiri (1 user = 1 toko). Tidak ada ganti/pindah toko,
    // tidak ada admin lintas-toko di aplikasi ini.
    Route::get('/stores/create', StoreCreate::class)->name('stores.create');

    Route::middleware(['store', 'subscription'])->group(function () {
        Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

        Route::get('/products', ProductIndex::class)->name('products.index');
        Route::get('/products/create', ProductForm::class)->name('products.create')->middleware('store.role:owner,stok');
        Route::get('/products/{id}/edit', ProductForm::class)->name('products.edit')->middleware('store.role:owner,stok');
        Route::get('/products/{id}/adjust', ProductAdjust::class)->name('products.adjust')->middleware('store.role:owner,stok');

        Route::get('/customers', CustomerIndex::class)->name('customers.index');
        Route::get('/customers/create', CustomerForm::class)->name('customers.create');
        Route::get('/customers/{id}/edit', CustomerForm::class)->name('customers.edit');
        Route::get('/customers/{id}', CustomerShow::class)->name('customers.show');
        Route::get('/customers/{customerId}/prescriptions/create', PrescriptionForm::class)->name('prescriptions.create');

        Route::get('/sales', SaleHistory::class)->name('sales.index');
        Route::get('/sales/{id}', SaleShow::class)->name('sales.show');

        Route::get('/optical-orders', OpticalOrderIndex::class)->name('optical-orders.index');
        Route::get('/optical-orders/create', OpticalOrderForm::class)->name('optical-orders.create');
        Route::get('/optical-orders/{id}/edit', OpticalOrderForm::class)->name('optical-orders.edit');
        Route::get('/optical-orders/receivables', OpticalOrderReceivables::class)->name('optical-orders.receivables')->middleware('store.role:owner,kasir,optician');
        Route::get('/optical-orders/{id}', OpticalOrderShow::class)->name('optical-orders.show');

        Route::get('/reports', ReportIndex::class)->name('reports.index')->middleware('store.role:owner');

        Route::get('/categories', CategoryIndex::class)->name('categories.index')->middleware('store.role:owner,stok');
        Route::get('/categories/create', Form::class)->name('categories.create')->middleware('store.role:owner,stok');
        Route::get('/categories/{id}/edit', Form::class)->name('categories.edit')->middleware('store.role:owner,stok');
        Route::get('/suppliers', SupplierIndex::class)->name('suppliers.index')->middleware('store.role:owner,stok');
        Route::get('/suppliers/create', App\Livewire\Suppliers\Form::class)->name('suppliers.create')->middleware('store.role:owner,stok');
        Route::get('/suppliers/{id}/edit', App\Livewire\Suppliers\Form::class)->name('suppliers.edit')->middleware('store.role:owner,stok');
        Route::get('/purchases', PurchaseIndex::class)->name('purchases.index')->middleware('store.role:owner,stok');
        Route::get('/expenses', ExpenseIndex::class)->name('expenses.index')->middleware('store.role:owner');
        Route::get('/expenses/create', App\Livewire\Expenses\Form::class)->name('expenses.create')->middleware('store.role:owner');
        Route::get('/expenses/{id}/edit', App\Livewire\Expenses\Form::class)->name('expenses.edit')->middleware('store.role:owner');
        Route::get('/returns', ReturnIndex::class)->name('returns.index')->middleware('store.role:owner,kasir');
        Route::get('/warranties', WarrantyIndex::class)->name('warranties.index');
        Route::get('/warranties/create', App\Livewire\Warranties\Form::class)->name('warranties.create');
        Route::get('/warranties/{id}/edit', App\Livewire\Warranties\Form::class)->name('warranties.edit');

        Route::get('/stock-opnames', Index::class)->name('stock-opnames.index')->middleware('store.role:owner,stok');
        Route::get('/stock-opnames/{id}', Count::class)->name('stock-opnames.count')->middleware('store.role:owner,stok');
        Route::get('/inventory', InventoryIndex::class)->name('inventory.index')->middleware('store.role:owner,stok');
        Route::get('/inventory/lens-grid/{productId}', LensGrid::class)->name('inventory.lens-grid')->middleware('store.role:owner,stok');

        Route::get('/pos/cashier', Cashier::class)->name('pos.cashier')->middleware('store.role:owner,kasir');
        Route::get('/lab/tracker', LabOrderTracker::class)->name('lab.tracker')->middleware('store.role:owner,optician');
        Route::get('/lab/qc', QcChecklist::class)->name('lab.qc')->middleware('store.role:owner,optician');

        Route::get('/settings/store', StoreSettings::class)->name('settings.store')->middleware('store.role:owner');
        Route::get('/settings/staff', StaffSettings::class)->name('settings.staff')->middleware('store.role:owner');
        Route::get('/billing', BillingIndex::class)->name('billing.index')->middleware('store.role:owner');

        Route::get('/print/sales/{id}', [PrintController::class, 'sale'])->name('print.sale');
        Route::get('/print/optical-orders/{id}', [PrintController::class, 'opticalOrder'])->name('print.optical-order');
        Route::get('/print/prescriptions/{id}', [PrintController::class, 'prescription'])->name('print.prescription');
        Route::get('/export/sales', [ExportController::class, 'sales'])->name('export.sales')->middleware('store.role:owner');
        Route::get('/export/stock', [ExportController::class, 'stock'])->name('export.stock')->middleware('store.role:owner,stok');
        Route::get('/export/stock-opname', [ExportController::class, 'stockOpname'])->name('export.stock-opname')->middleware('store.role:owner,stok');
    });

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
