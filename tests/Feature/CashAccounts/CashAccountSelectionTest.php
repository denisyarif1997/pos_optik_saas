<?php

namespace Tests\Feature\CashAccounts;

use App\Livewire\CashIns\Form as CashInForm;
use App\Livewire\CashIns\Index as CashInIndex;
use App\Livewire\Expenses\Form as ExpenseForm;
use App\Livewire\Expenses\Index as ExpenseIndex;
use App\Models\CashAccount;
use App\Models\CashIn;
use App\Models\Expense;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CashAccountSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cash_in_form_loads_and_links_the_selected_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();

        Livewire::test(CashInForm::class)
            ->assertSet('cashAccountId', $cash->id)
            ->set('description', 'Setoran pelanggan')
            ->set('amount', 150000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cash_ins', [
            'store_id' => $store->id,
            'cash_account_id' => $cash->id,
            'description' => 'Setoran pelanggan',
        ]);
    }

    public function test_expense_form_loads_and_links_the_selected_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();
        $this->fundCashAccount($cash, $user, 300000);

        Livewire::test(ExpenseForm::class)
            ->assertSet('cashAccountId', $cash->id)
            ->set('description', 'Tagihan listrik')
            ->set('amount', 250000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', [
            'store_id' => $store->id,
            'cash_account_id' => $cash->id,
            'description' => 'Tagihan listrik',
        ]);
    }

    public function test_inline_cash_in_form_requires_a_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $bank = CashAccount::forStore($store->id)->where('code', 'kas-bank')->firstOrFail();

        Livewire::test(CashInIndex::class)
            ->set('cashAccountId', null)
            ->set('description', 'Tanpa akun')
            ->set('amount', 10000)
            ->call('save')
            ->assertHasErrors(['cashAccountId' => 'required']);

        $this->assertDatabaseCount('cash_ins', 0);

        Livewire::test(CashInIndex::class)
            ->set('cashAccountId', $bank->id)
            ->set('description', 'Transfer bank')
            ->set('amount', 10000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cash_ins', [
            'store_id' => $store->id,
            'cash_account_id' => $bank->id,
        ]);
    }

    public function test_inline_expense_form_requires_a_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);

        Livewire::test(ExpenseIndex::class)
            ->set('cashAccountId', null)
            ->set('description', 'Tanpa akun')
            ->set('amount', 10000)
            ->call('save')
            ->assertHasErrors(['cashAccountId' => 'required']);

        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_inline_expense_form_blocks_saving_when_the_account_is_empty(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();

        Livewire::test(ExpenseIndex::class)
            ->set('showForm', true)
            ->set('cashAccountId', $cash->id)
            ->assertSee('Tidak bisa mencatat pengeluaran karena nilai kas akun ini 0.')
            ->set('description', 'Listrik')
            ->set('amount', 50000)
            ->call('save')
            ->assertHasErrors(['cashAccountId']);

        $this->assertDatabaseCount('expenses', 0);

        $this->fundCashAccount($cash, $user, 100000);

        Livewire::test(ExpenseIndex::class)
            ->set('cashAccountId', $cash->id)
            ->set('description', 'Listrik')
            ->set('amount', 50000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', [
            'store_id' => $store->id,
            'cash_account_id' => $cash->id,
            'description' => 'Listrik',
        ]);
    }

    public function test_cannot_select_an_inactive_or_foreign_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        [$otherUser, $otherStore] = $this->makeStoreWithOwner();
        $this->actingAs($user);

        $ownInactive = CashAccount::forStore($store->id)->where('code', 'kas-bank')->firstOrFail();
        $ownInactive->update(['is_active' => false]);
        $foreign = CashAccount::forStore($otherStore->id)->where('code', 'kas-toko')->firstOrFail();

        Livewire::test(ExpenseIndex::class)
            ->set('cashAccountId', $ownInactive->id)
            ->set('description', 'Akun nonaktif')
            ->set('amount', 10000)
            ->call('save')
            ->assertHasErrors(['cashAccountId' => 'exists']);

        Livewire::test(ExpenseIndex::class)
            ->set('cashAccountId', $foreign->id)
            ->set('description', 'Akun toko lain')
            ->set('amount', 10000)
            ->call('save')
            ->assertHasErrors(['cashAccountId' => 'exists']);

        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_cash_in_and_expense_history_show_the_cash_account_name(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $bank = CashAccount::forStore($store->id)->where('code', 'kas-bank')->firstOrFail();

        CashIn::create([
            'store_id' => $store->id,
            'created_by' => $user->id,
            'received_at' => now()->toDateString(),
            'category' => 'lainnya',
            'description' => 'Masuk via bank',
            'amount' => 50000,
            'cash_account_id' => $bank->id,
        ]);

        Expense::create([
            'store_id' => $store->id,
            'created_by' => $user->id,
            'spent_at' => now()->toDateString(),
            'category' => 'operasional',
            'description' => 'Keluar via bank',
            'amount' => 25000,
            'cash_account_id' => $bank->id,
        ]);

        Livewire::test(CashInIndex::class)
            ->assertSee('Masuk via bank')
            ->assertSee('Kas Bank');

        Livewire::test(ExpenseIndex::class)
            ->assertSee('Keluar via bank')
            ->assertSee('Kas Bank');
    }

    public function test_cash_in_form_shows_the_current_balance_of_each_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();

        CashIn::create([
            'store_id' => $store->id,
            'created_by' => $user->id,
            'received_at' => now()->toDateString(),
            'category' => 'lainnya',
            'description' => 'Modal awal',
            'amount' => 400000,
            'cash_account_id' => $cash->id,
        ]);

        Livewire::test(CashInForm::class)
            ->assertSee('Saldo saat ini')
            ->assertSee('Rp 400.000');
    }

    public function test_expense_form_blocks_saving_when_the_selected_account_is_empty(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();

        Livewire::test(ExpenseForm::class)
            ->assertSet('cashAccountId', $cash->id)
            ->assertSee('Tidak bisa mencatat pengeluaran karena nilai kas akun ini 0.')
            ->set('description', 'Listrik')
            ->set('amount', 50000)
            ->call('save')
            ->assertHasErrors(['cashAccountId']);

        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_expense_form_blocks_saving_when_switching_to_an_empty_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();
        $bank = CashAccount::forStore($store->id)->where('code', 'kas-bank')->firstOrFail();
        $this->fundCashAccount($cash, $user, 100000);

        Livewire::test(ExpenseForm::class)
            ->assertSet('cashAccountId', $cash->id)
            ->assertDontSee('Tidak bisa mencatat pengeluaran')
            ->set('cashAccountId', $bank->id)
            ->assertSee('Tidak bisa mencatat pengeluaran karena nilai kas akun ini 0.')
            ->set('description', 'Sewa')
            ->set('amount', 50000)
            ->call('save')
            ->assertHasErrors(['cashAccountId']);

        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_expense_form_saves_when_the_selected_account_has_a_balance(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();
        $this->fundCashAccount($cash, $user, 500000);

        Livewire::test(ExpenseForm::class)
            ->assertDontSee('Tidak bisa mencatat pengeluaran')
            ->set('description', 'Listrik')
            ->set('amount', 125000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', [
            'store_id' => $store->id,
            'cash_account_id' => $cash->id,
            'description' => 'Listrik',
        ]);
    }

    public function test_editing_an_expense_is_not_blocked_by_its_own_deduction(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();
        $this->fundCashAccount($cash, $user, 200000);

        $expense = Expense::create([
            'store_id' => $store->id,
            'created_by' => $user->id,
            'spent_at' => now()->toDateString(),
            'category' => 'operasional',
            'description' => 'Habiskan saldo',
            'amount' => 200000,
            'cash_account_id' => $cash->id,
        ]);

        // The balance is now 0 because of this very expense; correcting it must still work.
        $this->assertSame(0.0, CashAccount::balancesForStore($store->id)[$cash->id]);

        Livewire::test(ExpenseForm::class, ['id' => $expense->id])
            ->assertDontSee('Tidak bisa mencatat pengeluaran')
            ->set('description', 'Habiskan saldo (diperbaiki)')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'description' => 'Habiskan saldo (diperbaiki)',
        ]);
    }

    public function test_expense_form_blocks_saving_when_the_amount_exceeds_the_account_balance(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();
        $this->fundCashAccount($cash, $user, 900000);

        Livewire::test(ExpenseForm::class)
            ->set('description', 'Renovasi')
            ->set('amount', 1000000)
            ->assertSee('Nominal pengeluaran melebihi saldo kas akun ini')
            ->call('save')
            ->assertHasErrors(['cashAccountId'])
            ->assertSee('melebihi saldo kas akun ini');

        $this->assertDatabaseCount('expenses', 0);

        // Spending exactly the available balance is still allowed.
        Livewire::test(ExpenseForm::class)
            ->set('description', 'Renovasi')
            ->set('amount', 900000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', [
            'store_id' => $store->id,
            'cash_account_id' => $cash->id,
            'description' => 'Renovasi',
        ]);
    }

    public function test_inline_expense_form_blocks_saving_when_the_amount_exceeds_the_account_balance(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();
        $this->fundCashAccount($cash, $user, 900000);

        Livewire::test(ExpenseIndex::class)
            ->set('showForm', true)
            ->set('cashAccountId', $cash->id)
            ->set('description', 'Renovasi')
            ->set('amount', 1000000)
            ->assertSee('Nominal pengeluaran melebihi saldo kas akun ini')
            ->call('save')
            ->assertHasErrors(['cashAccountId']);

        $this->assertDatabaseCount('expenses', 0);

        Livewire::test(ExpenseIndex::class)
            ->set('cashAccountId', $cash->id)
            ->set('description', 'Renovasi')
            ->set('amount', 900000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', [
            'store_id' => $store->id,
            'cash_account_id' => $cash->id,
            'description' => 'Renovasi',
        ]);
    }

    public function test_editing_an_expense_cannot_exceed_the_balance_including_its_own_amount(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();
        $this->fundCashAccount($cash, $user, 900000);

        $expense = Expense::create([
            'store_id' => $store->id,
            'created_by' => $user->id,
            'spent_at' => now()->toDateString(),
            'category' => 'operasional',
            'description' => 'Sewa',
            'amount' => 500000,
            'cash_account_id' => $cash->id,
        ]);

        // Remaining balance is 400.000; with its own 500.000 added back the ceiling is 900.000.
        Livewire::test(ExpenseForm::class, ['id' => $expense->id])
            ->set('amount', 950000)
            ->call('save')
            ->assertHasErrors(['cashAccountId']);

        $this->assertSame('500000.00', $expense->fresh()->amount);

        Livewire::test(ExpenseForm::class, ['id' => $expense->id])
            ->set('amount', 900000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('900000.00', $expense->fresh()->amount);
    }

    private function fundCashAccount(CashAccount $account, User $user, float $amount): void
    {
        CashIn::create([
            'store_id' => $account->store_id,
            'created_by' => $user->id,
            'received_at' => now()->toDateString(),
            'category' => 'lainnya',
            'description' => 'Saldo awal',
            'amount' => $amount,
            'cash_account_id' => $account->id,
        ]);
    }

    /**
     * @return array{User, Store}
     */
    private function makeStoreWithOwner(): array
    {
        $user = User::factory()->create();
        $store = Store::create([
            'name' => 'Toko Pilih Kas',
            'slug' => 'toko-pilih-kas-'.str()->random(8),
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }
}
