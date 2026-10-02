<?php

use App\Models\Agency;
use App\Models\GroupPermission;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $group = GroupPermission::firstOrCreate(
            ['name' => 'Đại lý'],
            ['description' => 'Quản lý dữ liệu của đại lý du lịch']
        );

        $access = Permission::firstOrCreate(
            ['name' => 'truy-cap-he-thong'],
            ['display_name' => 'Truy cập trang quản trị', 'group_permission_id' => $group->id]
        );
        $manage = Permission::firstOrCreate(
            ['name' => 'quan-ly-dai-ly'],
            ['display_name' => 'Quản lý đại lý du lịch', 'description' => 'Quản lý tour, đăng ký và báo cáo thuộc đại lý', 'group_permission_id' => $group->id]
        );

        $role = Role::firstOrCreate(
            ['name' => 'dai-ly-du-lich'],
            ['display_name' => 'Đại lý du lịch', 'description' => 'Nhân sự quản lý dữ liệu đại lý của mình']
        );
        $role->permissionRole()->syncWithoutDetaching([$access->id, $manage->id]);

        $superAdmin = Role::where('name', 'super-admin')->first();
        $superAdmin?->permissionRole()->syncWithoutDetaching([$manage->id]);

        // Preserve access for profiles provisioned before the staff role existed.
        Agency::query()->pluck('user_id')->each(function ($userId) use ($role) {
            $role->users()->syncWithoutDetaching([$userId]);
        });
    }

    public function down(): void
    {
        // ACL records and staff assignments are preserved on rollback.
    }
};
