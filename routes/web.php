<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\PrintController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Billing\Index as BillingIndex;
use App\Livewire\CashAccounts\Index as CashAccountIndex;
use App\Livewire\CashIns\Index as CashInIndex;
use App\Livewire\Categories\Form;
use App\Livewire\Categories\Index as CategoryIndex;
use App\Livewire\Customers\Form as CustomerForm;
use App\Livewire\Customers\Index as CustomerIndex;
use App\Livewire\Customers\Show as CustomerShow;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Expenses\Index as ExpenseIndex;
use App\Livewire\Inventory\Index as InventoryIndex;
use App\Livewire\Inventory\LensGrid;
use App\Livewire\Inventory\StockHistory;
use App\Livewire\Pos\Cashier;
use App\Livewire\Prescriptions\Form as PrescriptionForm;
use App\Livewire\Products\Adjust as ProductAdjust;
use App\Livewire\Products\Form as ProductForm;
use App\Livewire\Products\Import as ProductImport;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Purchases\Index as PurchaseIndex;
use App\Livewire\Reports\CashInReport;
use App\Livewire\Reports\CashOutReport;
use App\Livewire\Reports\Index as ReportIndex;
use App\Livewire\Returns\Index as ReturnIndex;
use App\Livewire\Sales\History as SaleHistory;
use App\Livewire\Sales\Show as SaleShow;
use App\Livewire\Settings\Staff as StaffSettings;
use App\Livewire\Settings\StoreSettings;
use App\Livewire\StockOpnames\Count;
use App\Livewire\StockOpnames\Index;
use App\Livewire\StockOpnames\Show;
use App\Livewire\Stores\Create as StoreCreate;
use App\Livewire\Suppliers\Index as SupplierIndex;
use App\Livewire\Warranties\Index as WarrantyIndex;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    // Verifikasi email
    Route::get('/email/verify', function () {
        return view('livewire.auth.verify-notice');
    })->middleware('throttle:6,1')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect()->route('dashboard')->with('verified', true);
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Tautan verifikasi telah dikirimkan ke email Anda.');
    })->middleware('throttle:6,1')->name('verification.resend');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Profil tokomu sendiri (1 user = 1 toko). Tidak ada ganti/pindah toko,
    // tidak ada admin lintas-toko di aplikasi ini.
    Route::get('/stores/create', StoreCreate::class)->name('stores.create');

    Route::middleware(['store', 'subscription'])->group(function () {
        Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

        Route::get('/products', ProductIndex::class)->name('products.index');
        Route::get('/products/create', ProductForm::class)->name('products.create')->middleware('store.role:owner,stok');
        Route::get('/products/{id}/edit', ProductForm::class)->name('products.edit')->middleware('store.role:owner,stok');
        Route::get('/products/{id}/adjust', ProductAdjust::class)->name('products.adjust')->middleware('store.role:owner,stok');
        Route::get('/products/import', ProductImport::class)->name('products.import')->middleware('store.role:owner,stok');

        Route::get('/customers', CustomerIndex::class)->name('customers.index');
        Route::get('/customers/create', CustomerForm::class)->name('customers.create');
        Route::get('/customers/{id}/edit', CustomerForm::class)->name('customers.edit');
        Route::get('/customers/{id}', CustomerShow::class)->name('customers.show');
        Route::get('/customers/{customerId}/prescriptions/create', PrescriptionForm::class)->name('prescriptions.create');

        Route::get('/sales', SaleHistory::class)->name('sales.index');
        Route::get('/sales/{id}', SaleShow::class)->name('sales.show');

        Route::get('/reports', ReportIndex::class)->name('reports.index')->middleware('store.role:owner');

        Route::get('/cash-reports/in', CashInReport::class)->name('cash-reports.in')->middleware('store.role:owner');
        Route::get('/cash-reports/out', CashOutReport::class)->name('cash-reports.out')->middleware('store.role:owner');

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
        Route::get('/cash-ins', CashInIndex::class)->name('cash-ins.index')->middleware('store.role:owner');
        Route::get('/cash-ins/create', App\Livewire\CashIns\Form::class)->name('cash-ins.create')->middleware('store.role:owner');
        Route::get('/cash-ins/{id}/edit', App\Livewire\CashIns\Form::class)->name('cash-ins.edit')->middleware('store.role:owner');
        Route::get('/returns', ReturnIndex::class)->name('returns.index')->middleware('store.role:owner,kasir');
        Route::get('/warranties', WarrantyIndex::class)->name('warranties.index');
        Route::get('/warranties/create', App\Livewire\Warranties\Form::class)->name('warranties.create');
        Route::get('/warranties/{id}/edit', App\Livewire\Warranties\Form::class)->name('warranties.edit');

        Route::get('/stock-opnames', Index::class)->name('stock-opnames.index')->middleware('store.role:owner,stok');
        Route::get('/stock-opnames/{id}', Count::class)->name('stock-opnames.count')->middleware('store.role:owner,stok');
        Route::get('/stock-opnames/{id}/show', Show::class)->name('stock-opnames.show')->middleware('store.role:owner,stok');
        Route::get('/inventory', InventoryIndex::class)->name('inventory.index')->middleware('store.role:owner,stok');
        Route::get('/inventory/lens-grid/{id}', LensGrid::class)->name('inventory.lens-grid')->middleware('store.role:owner,stok');
        Route::get('/inventory/stock-history', StockHistory::class)->name('inventory.stock-history')->middleware('store.role:owner,stok');

        Route::get('/pos/cashier', Cashier::class)->name('pos.cashier')->middleware('store.role:owner,kasir');

        Route::get('/settings/store', StoreSettings::class)->name('settings.store')->middleware('store.role:owner');
        Route::get('/settings/staff', StaffSettings::class)->name('settings.staff')->middleware('store.role:owner');
        Route::get('/billing', BillingIndex::class)->name('billing.index')->middleware('store.role:owner');
        Route::get('/cash-accounts', CashAccountIndex::class)->name('cash-accounts.index')->middleware('store.role:owner');

        Route::get('/print/sales/{id}', [PrintController::class, 'sale'])->name('print.sale');
        Route::get('/print/prescriptions/{id}', [PrintController::class, 'prescription'])->name('print.prescription');
        Route::get('/export/sales', [ExportController::class, 'sales'])->name('export.sales')->middleware('store.role:owner');
        Route::get('/export/transactions', [ExportController::class, 'transactions'])->name('export.transactions')->middleware('store.role:owner');
        Route::get('/export/stock', [ExportController::class, 'stock'])->name('export.stock')->middleware('store.role:owner,stok');
        Route::get('/export/stock-history', [ExportController::class, 'stockHistory'])->name('export.stock-history')->middleware('store.role:owner,stok');
        Route::get('/export/stock-opname', [ExportController::class, 'stockOpname'])->name('export.stock-opname')->middleware('store.role:owner,stok');
    });

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
