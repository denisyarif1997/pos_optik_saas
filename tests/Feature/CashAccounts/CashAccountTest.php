<?php

namespace Tests\Feature\CashAccounts;

use App\Livewire\CashAccounts\Index;
use App\Models\CashAccount;
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
