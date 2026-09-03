<?php

namespace Tests\Feature;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Utama',
            'username' => 'admin_test',
            'role' => Role::ADMIN,
            'nama_unit' => 'Administrator Sarpras',
            'kode_unit' => 'ADM',
        ]);

        $this->regularUser = User::factory()->create([
            'name' => 'Unit Kehumasan',
            'username' => 'uk_test',
            'role' => Role::USER,
            'nama_unit' => 'Unit Kehumasan',
            'kode_unit' => 'HUMAS',
            'password_plain' => 'kpwbisulut',
        ]);
    }

    public function test_admin_can_view_user_management_with_passwords(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Data User');
        $response->assertSee('kpwbisulut');
        $response->assertSee('uk_test');
    }

    public function test_admin_cannot_delete_an_admin_account(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_cannot_delete_another_admin_account(): void
    {
        $secondAdmin = User::factory()->create([
            'name' => 'Admin Cadangan',
            'username' => 'admin_dua',
            'role' => Role::ADMIN,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $secondAdmin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $secondAdmin->id]);
    }

    public function test_admin_can_update_own_name_and_password(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->admin), [
            'username' => 'admin_baru',
            'name' => 'Kepala Sarpras Baru',
            'role' => 'admin',
            'password' => 'passwordbaru123',
        ]);

        $response->assertSessionHas('success');
        $this->admin->refresh();
        $this->assertEquals('admin_baru', $this->admin->username);
        $this->assertEquals('Kepala Sarpras Baru', $this->admin->name);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('passwordbaru123', $this->admin->password));
    }

    public function test_admin_updating_user_password_updates_plain_password(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->regularUser), [
            'username' => $this->regularUser->username,
            'name' => $this->regularUser->name,
            'role' => 'user',
            'nama_unit' => $this->regularUser->nama_unit,
            'kode_unit' => $this->regularUser->kode_unit,
            'password' => 'newuserpass123',
        ]);

        $response->assertSessionHas('success');
        $this->regularUser->refresh();
        $this->assertEquals('newuserpass123', $this->regularUser->password_plain);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newuserpass123', $this->regularUser->password));
    }

    public function test_admin_role_cannot_be_changed_to_user(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->admin), [
            'username' => $this->admin->username,
            'name' => $this->admin->name,
            'role' => 'user', // Coba ubah role admin menjadi user
        ]);

        $this->admin->refresh();
        $this->assertEquals(Role::ADMIN, $this->admin->role);
    }
}
