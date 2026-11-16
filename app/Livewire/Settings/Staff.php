<?php

namespace App\Livewire\Settings;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Staff extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'kasir';

    public ?int $editingUserId = null;

    public string $editingRole = 'kasir';

    public array $roles = ['owner', 'stok', 'kasir', 'optician'];

    public function addStaff(): void
    {
        $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:owner,stok,kasir,optician',
        ]);

        $store = $this->store();
        abort_unless($store, 403);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $store->members()->attach($user->id, [
            'role' => $this->role,
            'is_active' => true,
        ]);

        $this->reset(['name', 'email', 'password']);
        $this->role = 'kasir';

        session()->flash('success', 'Staf baru ditambahkan.');
    }

    public function startEditRole(int $userId): void
    {
        $store = $this->store();
        abort_unless($store, 403);

        $member = $store->members()->where('users.id', $userId)->first();
        abort_unless($member, 404);

        $this->editingUserId = $userId;
        $this->editingRole = $member->pivot->role;
    }

    public function cancelEditRole(): void
    {
        $this->editingUserId = null;
        $this->editingRole = 'kasir';
    }

    public function saveRole(): void
    {
        $this->validate([
            'editingRole' => 'required|in:owner,stok,kasir,optician',
        ]);

        $store = $this->store();
        abort_unless($store, 403);

        $member = $store->members()->where('users.id', $this->editingUserId)->first();
        abort_unless($member, 404);

        // Prevent removing the last owner
        if ($member->pivot->role === 'owner' && $this->editingRole !== 'owner') {
            $ownerCount = $store->members()->wherePivot('role', 'owner')->wherePivot('is_active', true)->count();
            if ($ownerCount <= 1) {
                session()->flash('error', 'Tidak bisa mengubah role: toko harus memiliki minimal 1 owner aktif.');

                return;
            }
        }

        $store->members()->updateExistingPivot($this->editingUserId, ['role' => $this->editingRole]);

        $this->cancelEditRole();
        session()->flash('success', 'Role staf diperbarui.');
    }

    public function toggleActive(int $userId): void
    {
        $store = $this->store();
        abort_unless($store, 403);

        // Prevent deactivating yourself
        if ($userId === auth()->id()) {
            session()->flash('error', 'Tidak bisa menonaktifkan akun sendiri.');

            return;
        }

        $member = $store->members()->where('users.id', $userId)->first();
        abort_unless($member, 404);

        $newState = ! (bool) $member->pivot->is_active;

        // Prevent deactivating the last active owner
        if ($member->pivot->role === 'owner' && ! $newState) {
            $activeOwners = $store->members()->wherePivot('role', 'owner')->wherePivot('is_active', true)->count();
            if ($activeOwners <= 1) {
                session()->flash('error', 'Tidak bisa menonaktifkan owner terakhir.');

                return;
            }
        }

        $store->members()->updateExistingPivot($userId, ['is_active' => $newState]);

        session()->flash('success', $newState ? 'Staf diaktifkan.' : 'Staf dinonaktifkan.');
    }

    public function resetPassword(int $userId): void
    {
        $store = $this->store();
        abort_unless($store, 403);

        $member = $store->members()->where('users.id', $userId)->first();
        abort_unless($member, 404);

        $newPassword = 'password123';
        $member->update(['password' => Hash::make($newPassword)]);

        session()->flash('success', "Password staf direset ke: {$newPassword}");
    }

    protected function store(): ?Store
    {
        return app('activeStore')->get();
    }

    public function render()
    {
        $store = $this->store();
        abort_unless($store, 403);

        $staff = $store->members()->orderBy('name')->get();

        return view('livewire.settings.staff', [
            'staff' => $staff,
            'currentUserId' => auth()->id(),
        ])->title('Manajemen Staf');
    }
}
