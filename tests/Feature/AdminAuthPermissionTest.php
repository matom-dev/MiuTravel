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
        $this->grantPermissions($admin, ['danh-sach-tour', 'full-quyen-quan-ly']);

        $this->assertTrue($admin->can('danh-sach-tour'));
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

    public function test_sensitive_public_and_admin_post_routes_are_throttled(): void
    {
        $expectedRoutes = [
            'account.login',
            'post.account.register',
            'contact.send',
            'post.book.tour',
            'comment',
            'reply.comment',
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
