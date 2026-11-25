<?php

namespace Tests\Feature\CashAccounts;

use App\Livewire\CashAccounts\Index;
use App\Models\CashAccount;
use App\Models\CashIn;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use Database\Seeders\CashAccountSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CashAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_and_edit_cash_accounts(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);

        $this->get(route('cash-accounts.index'))
            ->assertOk()
            ->assertSee('Kas Toko')
            ->assertSee('Kas Bank');

        Livewire::test(Index::class)
            ->set('name', 'Kas Cadangan')
            ->set('type', 'bank')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Kas Cadangan');

        $account = CashAccount::forStore($store->id)->where('name', 'Kas Cadangan')->firstOrFail();
        $this->assertSame('bank', $account->type);

        Livewire::test(Index::class)
            ->call('edit', $account->id)
            ->set('name', 'Bank Operasional')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cash_accounts', [
            'id' => $account->id,
            'name' => 'Bank Operasional',
            'type' => 'bank',
        ]);
    }

    public function test_cash_account_seeder_is_idempotent_for_existing_stores(): void
    {
        [, $store] = $this->makeStoreWithOwner();
        CashAccount::forStore($store->id)->delete();

        $this->seed(CashAccountSeeder::class);
        $this->seed(CashAccountSeeder::class);

        $this->assertDatabaseCount('cash_accounts', 2);
        $this->assertDatabaseHas('cash_accounts', [
            'store_id' => $store->id,
            'code' => 'kas-toko',
            'name' => 'Kas Toko',
        ]);
        $this->assertDatabaseHas('cash_accounts', [
            'store_id' => $store->id,
            'code' => 'kas-bank',
            'name' => 'Kas Bank',
        ]);
    }

    public function test_owner_cannot_disable_every_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $accounts = CashAccount::forStore($store->id)->orderBy('id')->get();

        Livewire::test(Index::class)
            ->call('toggleActive', $accounts[0]->id)
            ->call('toggleActive', $accounts[1]->id)
            ->assertSee('Minimal satu akun kas harus tetap aktif.');

        $this->assertSame(1, CashAccount::forStore($store->id)->where('is_active', true)->count());
    }

    public function test_non_owner_cannot_open_cash_account_master(): void
    {
        $staff = User::factory()->create();
        $store = Store::create([
            'name' => 'Toko Staf Kas',
            'slug' => 'toko-staf-kas-'.str()->random(8),
        ]);
        $store->members()->attach($staff->id, ['role' => 'kasir', 'is_active' => true]);
        $this->actingAs($staff);

        $this->get(route('cash-accounts.index'))->assertForbidden();
    }

    public function test_owner_cannot_edit_another_stores_cash_account(): void
    {
        [$user] = $this->makeStoreWithOwner();
        [, $otherStore] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $otherAccount = CashAccount::forStore($otherStore->id)->firstOrFail();

        Livewire::test(Index::class)
            ->call('edit', $otherAccount->id)
            ->assertStatus(404);
    }

    public function test_master_cash_shows_balance_derived_from_payments_cash_ins_and_expenses(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();
        $bank = CashAccount::forStore($store->id)->where('code', 'kas-bank')->firstOrFail();

        $sale = Sale::create([
            'store_id' => $store->id,
            'cashier_id' => $user->id,
            'code' => 'INV-BALANCE',
            'sold_at' => now(),
            'subtotal' => 500000,
            'discount_total' => 0,
            'total' => 500000,
            'paid_total' => 500000,
            'payment_status' => 'lunas',
            'status' => 'selesai',
        ]);

        Payment::create([
            'store_id' => $store->id,
            'sale_id' => $sale->id,
            'received_by' => $user->id,
            'cash_account_id' => $cash->id,
            'paid_at' => now(),
            'method' => 'cash',
            'amount' => 500000,
        ]);

        CashIn::create([
            'store_id' => $store->id,
            'created_by' => $user->id,
            'received_at' => now()->toDateString(),
            'category' => 'lainnya',
            'description' => 'Tambahan modal',
            'amount' => 100000,
            'cash_account_id' => $cash->id,
        ]);

        Expense::create([
            'store_id' => $store->id,
            'created_by' => $user->id,
            'spent_at' => now()->toDateString(),
            'category' => 'operasional',
            'description' => 'Listrik',
            'amount' => 50000,
            'cash_account_id' => $cash->id,
        ]);

        Expense::create([
            'store_id' => $store->id,
            'created_by' => $user->id,
            'spent_at' => now()->toDateString(),
            'category' => 'sewa',
            'description' => 'Sewa',
            'amount' => 20000,
            'cash_account_id' => $bank->id,
        ]);

        $balances = CashAccount::balancesForStore($store->id);

        $this->assertSame(550000.0, $balances[$cash->id]);
        $this->assertSame(-20000.0, $balances[$bank->id]);

        // Total card counts only active accounts: 550000 - 20000.
        Livewire::test(Index::class)
            ->assertSee('Kas Toko')
            ->assertSee('Rp 550.000')
            ->assertSee('Rp -20.000')
            ->assertSee('Rp 530.000');
    }

    public function test_balance_ignores_records_without_a_cash_account(): void
    {
        [, $store] = $this->makeStoreWithOwner();
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();

        Expense::create([
            'store_id' => $store->id,
            'spent_at' => now()->toDateString(),
            'category' => 'operasional',
            'description' => 'Tanpa akun kas',
            'amount' => 75000,
        ]);

        $balances = CashAccount::balancesForStore($store->id);

        $this->assertArrayNotHasKey($cash->id, $balances);
        $this->assertSame([], $balances);
    }

    public function test_balance_is_isolated_per_store(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner();
        [, $storeB] = $this->makeStoreWithOwner();
        $accountA = CashAccount::forStore($storeA->id)->where('code', 'kas-toko')->firstOrFail();
        $accountB = CashAccount::forStore($storeB->id)->where('code', 'kas-toko')->firstOrFail();

        CashIn::create([
            'store_id' => $storeA->id,
            'created_by' => $userA->id,
            'received_at' => now()->toDateString(),
            'category' => 'lainnya',
            'description' => 'Milik toko A',
            'amount' => 300000,
            'cash_account_id' => $accountA->id,
        ]);

        $balancesA = CashAccount::balancesForStore($storeA->id);
        $balancesB = CashAccount::balancesForStore($storeB->id);

        $this->assertSame(300000.0, $balancesA[$accountA->id]);
        $this->assertArrayNotHasKey($accountB->id, $balancesB);
    }

    public function test_active_scope_and_default_account_prefer_cash_type(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);

        $bank = CashAccount::forStore($store->id)->where('code', 'kas-bank')->firstOrFail();
        $cash = CashAccount::forStore($store->id)->where('code', 'kas-toko')->firstOrFail();

        $this->assertSame(2, CashAccount::forStore($store->id)->active()->count());
        $this->assertSame($cash->id, CashAccount::defaultActiveAccountIdForStore($store->id));

        // "Kas Bank" sorts first alphabetically, so the cash-type preference is what picks Kas Toko.
        $this->assertSame($bank->id, CashAccount::forStore($store->id)->active()->orderBy('name')->value('id'));

        $bank->update(['is_active' => false]);

        $this->assertSame(1, CashAccount::forStore($store->id)->active()->count());
        $this->assertSame([$cash->id => 'Kas Toko'], CashAccount::activeOptionsForStore($store->id));
        $this->assertSame($cash->id, CashAccount::defaultActiveAccountIdForStore($store->id));
    }

    /**
     * @return array{User, Store}
     */
    private function makeStoreWithOwner(): array
    {
        $user = User::factory()->create();
        $store = Store::create([
            'name' => 'Toko Akun Kas',
            'slug' => 'toko-akun-kas-'.str()->random(8),
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }
}
