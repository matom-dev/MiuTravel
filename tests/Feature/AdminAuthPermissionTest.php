<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminAuthPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_requires_admin_access_permission(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'status' => 1,
        ]);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'secret123',
        ])->assertRedirect(route('admin.login'))
            ->assertSessionHas('danger');

        $this->assertGuest('admins');

        $this->grantPermissions($admin, ['truy-cap-he-thong']);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'secret123',
        ])->assertRedirect(route('admin.home'));

        $this->assertAuthenticatedAs($admin, 'admins');
    }

    public function test_role_permissions_are_resolved_without_entrust_package(): void
    {
        $admin = User::factory()->create();
        $this->grantPermissions($admin, ['quan-ly-tour', 'full-quyen-quan-ly']);

        $this->assertTrue($admin->can('quan-ly-tour'));
        $this->assertTrue($admin->can(['xoa-tour', 'full-quyen-quan-ly']));
        $this->assertFalse($admin->can('xoa-tour'));
    }

    public function test_permission_middleware_blocks_admin_without_required_permission(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin, 'admins')
            ->post('/clear-cache')
            ->assertForbidden();

        $this->grantPermissions($admin, ['full-quyen-quan-ly']);

        $this->actingAs($admin->fresh(), 'admins')
            ->from('/admin/home')
            ->post('/clear-cache')
            ->assertRedirect('/admin/home');
    }

    public function test_permission_catalog_routes_are_super_admin_only(): void
    {
        $admin = User::factory()->create();
        $this->grantPermissions($admin, ['truy-cap-he-thong']);

        $this->actingAs($admin, 'admins')
            ->get(route('permission.index'))
            ->assertForbidden();

        $this->actingAs($admin, 'admins')
            ->get(route('group.permission.create'))
            ->assertForbidden();

        $this->actingAs($admin, 'admins')
            ->get(route('role.index'))
            ->assertForbidden();

        $this->actingAs($admin, 'admins')
            ->get(route('user.index'))
            ->assertForbidden();

        $superAdmin = User::factory()->create();
        $this->grantPermissions($superAdmin, ['full-quyen-quan-ly']);

        $this->actingAs($superAdmin, 'admins')
            ->get(route('permission.index'))
            ->assertOk();

        $this->actingAs($superAdmin, 'admins')
            ->get(route('role.index'))
            ->assertOk();

        $this->actingAs($superAdmin, 'admins')
            ->get(route('user.index'))
            ->assertOk();
    }

    public function test_role_update_can_remove_all_permissions(): void
    {
        $admin = User::factory()->create();
        $this->grantPermissions($admin, ['full-quyen-quan-ly']);

        $role = Role::create([
            'name' => 'content-editor',
            'display_name' => 'Content editor',
        ]);
        $permission = Permission::create([
            'name' => 'quan-ly-noi-dung',
            'display_name' => 'Quản lý nội dung',
        ]);
        $role->permissionRole()->attach($permission->id);

        $this->actingAs($admin, 'admins')
            ->post(route('role.update', $role->id), [
                'name' => 'Content editor updated',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('permission_role', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
    }

    public function test_user_management_separates_staff_and_customer_lists(): void
    {
        $admin = User::factory()->create(['name' => 'Root Manager']);
        $this->grantPermissions($admin, ['full-quyen-quan-ly']);

        $staffRole = Role::create([
            'name' => 'nhan-vien-booking',
            'display_name' => 'Nhân viên booking',
        ]);
        $customerRole = Role::create([
            'name' => 'khach-hang',
            'display_name' => 'Khách hàng',
        ]);

        $staff = User::factory()->create([
            'name' => 'Noi Bo Alpha',
            'email' => 'staff-alpha@example.test',
        ]);
        $staff->userRole()->attach($staffRole->id);

        $customer = User::factory()->create([
            'name' => 'Khach Hang Beta',
            'email' => 'customer-beta@example.test',
        ]);
        $customer->userRole()->attach($customerRole->id);

        $unassignedCustomer = User::factory()->create([
            'name' => 'Khach Hang Chua Gan Role',
            'email' => 'customer-unassigned@example.test',
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('user.index'))
            ->assertOk()
            ->assertSee('Nhân sự công ty')
            ->assertSee('Noi Bo Alpha')
            ->assertDontSee('Khach Hang Beta')
            ->assertDontSee('Khach Hang Chua Gan Role');

        $this->actingAs($admin, 'admins')
            ->get(route('user.customer.index'))
            ->assertOk()
            ->assertSee('Danh sách khách hàng')
            ->assertSee('Khach Hang Beta')
            ->assertSee('Khach Hang Chua Gan Role')
            ->assertDontSee('Noi Bo Alpha');
    }

    public function test_booking_view_permission_cannot_update_or_delete_booking(): void
    {
        $admin = User::factory()->create();
        $this->grantPermissions($admin, ['xem-dat-tour']);

        $this->actingAs($admin, 'admins')
            ->patch('/admin/book-tour/update/2/999')
            ->assertForbidden();

        $this->actingAs($admin, 'admins')
            ->delete('/admin/book-tour/delete/999')
            ->assertForbidden();
    }

    public function test_admin_staff_can_change_own_password(): void
    {
        $admin = User::factory()->create([
            'password' => Hash::make('old-secret'),
            'status' => 1,
        ]);
        $this->grantPermissions($admin, ['truy-cap-he-thong']);

        $this->actingAs($admin, 'admins')
            ->get(route('admin.change.password'))
            ->assertOk()
            ->assertSee('Đổi mật khẩu');

        $this->actingAs($admin, 'admins')
            ->post(route('admin.update.password'), [
                'current_password' => 'wrong-secret',
                'password' => 'new-secret-123',
                'password_confirmation' => 'new-secret-123',
            ])
            ->assertSessionHasErrors('current_password');

        $this->actingAs($admin->fresh(), 'admins')
            ->post(route('admin.update.password'), [
                'current_password' => 'old-secret',
                'password' => 'new-secret-123',
                'password_confirmation' => 'new-secret-123',
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-secret-123', $admin->fresh()->password));
        $this->assertGuest('admins');
    }

    public function test_sensitive_public_and_admin_post_routes_are_throttled(): void
    {
        $expectedRoutes = [
            'account.login',
            'post.account.register',
            'contact.send',
            'post.book.tour',
            'comment',
            'reply.comment',
            'admin.update.password',
        ];

        foreach ($expectedRoutes as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route, 'Route missing: ' . $routeName);
            $this->assertTrue(
                collect($route->gatherMiddleware())->contains(fn ($middleware) => str_starts_with($middleware, 'throttle:')),
                'Route is missing throttle middleware: ' . $routeName
            );
        }

        $adminLogin = collect(Route::getRoutes()->match(\Illuminate\Http\Request::create('/admin/login', 'POST'))->gatherMiddleware());
        $this->assertTrue($adminLogin->contains(fn ($middleware) => str_starts_with($middleware, 'throttle:')));
    }

    private function grantPermissions(User $user, array $permissionNames): void
    {
        $role = Role::create([
            'name' => 'admin-role-' . $user->id,
            'display_name' => 'Admin role',
        ]);

        foreach ($permissionNames as $permissionName) {
            $permission = Permission::create([
                'name' => $permissionName,
                'display_name' => $permissionName,
            ]);

            $role->permissionRole()->attach($permission->id);
        }

        $user->userRole()->attach($role->id);
        $user->unsetRelation('userRole');
    }
}
