<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CategoryAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_open_category_creation_page(): void
    {
        $user = $this->createStoreMember('owner', 'owner-category@example.test');

        $this->actingAs($user)
            ->get(route('categories.create'))
            ->assertOk()
            ->assertSee('Form Kategori Baru');
    }

    #[DataProvider('categoryCreationPermissions')]
    public function test_category_creation_is_allowed_only_for_owner_and_stock_staff(string $role, bool $allowed): void
    {
        $user = $this->createStoreMember($role, $role.'-category@example.test');

        $this->assertSame($allowed, $user->can('create', Category::class));
    }

    public function test_category_updates_are_limited_to_allowed_roles_in_the_active_store(): void
    {
        $owner = $this->createStoreMember('owner', 'owner-category-update@example.test');
        $store = $owner->store();
        $category = Category::make(['store_id' => $store->id]);

        $stockStaff = User::factory()->create(['email' => 'stock-category-update@example.test']);
        $store->members()->attach($stockStaff->id, ['role' => 'stok', 'is_active' => true]);
        $cashier = User::factory()->create(['email' => 'cashier-category-update@example.test']);
        $store->members()->attach($cashier->id, ['role' => 'kasir', 'is_active' => true]);
        $otherStoreOwner = $this->createStoreMember('owner', 'other-owner-category-update@example.test');
        $otherStoreCategory = Category::make(['store_id' => $otherStoreOwner->storeId()]);

        $this->assertTrue($owner->can('update', $category));
        $this->assertTrue($stockStaff->can('update', $category));
        $this->assertFalse($cashier->can('update', $category));
        $this->assertFalse($owner->can('update', $otherStoreCategory));
    }

    public function test_cashier_is_forbidden_from_category_creation_page(): void
    {
        $cashier = $this->createStoreMember('kasir', 'cashier-category-page@example.test');

        $this->actingAs($cashier)
            ->get(route('categories.create'))
            ->assertForbidden();
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function categoryCreationPermissions(): array
    {
        return [
            'owner' => ['owner', true],
            'stock staff' => ['stok', true],
            'cashier' => ['kasir', false],
            'optician' => ['optician', false],
        ];
    }

    private function createStoreMember(string $role, string $email): User
    {
        $user = User::factory()->create(['email' => $email]);
        $store = Store::create([
            'name' => 'Toko '.$email,
            'slug' => str()->slug($email),
        ]);
        $store->members()->attach($user->id, ['role' => $role, 'is_active' => true]);

        return $user->fresh();
    }
}
