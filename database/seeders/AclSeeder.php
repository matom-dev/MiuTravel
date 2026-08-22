<?php

namespace Database\Seeders;

use App\Models\GroupPermission;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AclSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'Hệ thống' => [
                'description' => 'Quyền truy cập và cấu hình hệ thống',
                'permissions' => [
                    'full-quyen-quan-ly' => 'Toàn quyền quản trị',
                    'truy-cap-he-thong' => 'Truy cập trang quản trị',
                    'quan-ly-vai-tro' => 'Quản lý vai trò',
                    'quan-ly-nguoi-dung' => 'Quản lý người dùng',
                ],
            ],
            'Báo cáo' => [
                'description' => 'Quyền xem dữ liệu báo cáo và doanh thu',
                'permissions' => [
                    'xem-dashboard' => 'Xem bảng điều khiển',
                    'xem-doanh-thu' => 'Xem doanh thu',
                    'xuat-bao-cao' => 'Xuất báo cáo',
                ],
            ],
            'Nội dung' => [
                'description' => 'Quyền quản trị bài viết, danh mục và địa điểm',
                'permissions' => [
                    'quan-ly-noi-dung' => 'Quản lý nội dung',
                    'xuat-ban-noi-dung' => 'Xuất bản nội dung',
                    'xoa-noi-dung' => 'Xóa nội dung',
                ],
            ],
            'Tour' => [
                'description' => 'Quyền quản lý tour và nhân sự tour',
                'permissions' => [
                    'quan-ly-tour' => 'Quản lý tour',
                    'duyet-xuat-ban-tour' => 'Duyệt và xuất bản tour',
                    'xoa-tour' => 'Xóa tour',
                    'quan-ly-nhan-su-tour' => 'Quản lý nhân sự tour',
                ],
            ],
            'Dịch vụ' => [
                'description' => 'Quyền quản lý dịch vụ phụ trợ',
                'permissions' => [
                    'quan-ly-khach-san' => 'Quản lý khách sạn',
                    'quan-ly-thue-xe' => 'Quản lý thuê xe',
                ],
            ],
            'Booking' => [
                'description' => 'Quyền vận hành đơn đặt tour',
                'permissions' => [
                    'xem-dat-tour' => 'Xem đặt tour',
                    'cap-nhat-trang-thai-dat-tour' => 'Cập nhật trạng thái đặt tour',
                    'xoa-dat-tour' => 'Xóa đặt tour',
                    'xuat-dat-tour' => 'Xuất danh sách đặt tour',
                ],
            ],
            'Tương tác' => [
                'description' => 'Quyền quản lý phản hồi khách hàng',
                'permissions' => [
                    'cham-soc-khach-hang' => 'Chăm sóc khách hàng',
                    'quan-ly-binh-luan' => 'Quản lý bình luận',
                ],
            ],
        ];

        $permissionIds = [];

        foreach ($groups as $groupName => $groupConfig) {
            $group = GroupPermission::updateOrCreate(
                ['name' => $groupName],
                ['description' => $groupConfig['description']]
            );

            foreach ($groupConfig['permissions'] as $permissionName => $displayName) {
                $permission = Permission::updateOrCreate(
                    ['name' => $permissionName],
                    [
                        'display_name' => $displayName,
                        'description' => $displayName,
                        'group_permission_id' => $group->id,
                    ]
                );

                $permissionIds[$permissionName] = $permission->id;
            }
        }

        $roles = [
            'super-admin' => [
                'display_name' => 'Super Admin',
                'description' => 'Toàn quyền quản trị hệ thống',
                'permissions' => array_keys($permissionIds),
            ],
            'quan-ly-van-hanh' => [
                'display_name' => 'Quản lý vận hành',
                'description' => 'Quản lý dịch vụ, booking và báo cáo vận hành',
                'permissions' => [
                    'truy-cap-he-thong',
                    'xem-dashboard',
                    'xem-doanh-thu',
                    'xuat-bao-cao',
                    'quan-ly-tour',
                    'quan-ly-nhan-su-tour',
                    'quan-ly-khach-san',
                    'quan-ly-thue-xe',
                    'xem-dat-tour',
                    'cap-nhat-trang-thai-dat-tour',
                    'xuat-dat-tour',
                    'quan-ly-binh-luan',
                ],
            ],
            'nhan-vien-booking' => [
                'display_name' => 'Nhân viên booking',
                'description' => 'Xử lý và cập nhật trạng thái booking',
                'permissions' => [
                    'truy-cap-he-thong',
                    'xem-dashboard',
                    'xem-dat-tour',
                    'cap-nhat-trang-thai-dat-tour',
                    'xuat-dat-tour',
                ],
            ],
            'cham-soc-khach-hang' => [
                'display_name' => 'Chăm sóc khách hàng',
                'description' => 'Theo dõi khách hàng, phản hồi bình luận và hỗ trợ trước/sau chuyến đi',
                'permissions' => [
                    'truy-cap-he-thong',
                    'xem-dashboard',
                    'xem-dat-tour',
                    'cham-soc-khach-hang',
                    'quan-ly-binh-luan',
                ],
            ],
            'bien-tap-noi-dung' => [
                'display_name' => 'Biên tập nội dung',
                'description' => 'Quản lý bài viết, danh mục và địa điểm',
                'permissions' => [
                    'truy-cap-he-thong',
                    'xem-dashboard',
                    'quan-ly-noi-dung',
                    'xuat-ban-noi-dung',
                ],
            ],
            'ke-toan-bao-cao' => [
                'display_name' => 'Kế toán/Báo cáo',
                'description' => 'Xem doanh thu và báo cáo tài chính',
                'permissions' => [
                    'truy-cap-he-thong',
                    'xem-dashboard',
                    'xem-doanh-thu',
                    'xuat-bao-cao',
                ],
            ],
        ];

        $superAdminRole = null;

        foreach ($roles as $roleName => $roleConfig) {
            $role = Role::updateOrCreate(
                ['name' => $roleName],
                [
                    'display_name' => $roleConfig['display_name'],
                    'description' => $roleConfig['description'],
                ]
            );

            $role->permissionRole()->sync(
                collect($roleConfig['permissions'])
                    ->map(fn ($permissionName) => $permissionIds[$permissionName] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );

            if ($roleName === 'super-admin') {
                $superAdminRole = $role;
            }
        }

        if ($superAdminRole) {
            $adminEmail = env('ADMIN_EMAIL', 'admin@gmail.com');
            $admin = User::firstOrNew(['email' => $adminEmail]);

            if (!$admin->exists) {
                $admin->forceFill([
                    'name' => env('ADMIN_NAME', 'Super Admin'),
                    'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                    'email_verified_at' => now(),
                ]);
            } else {
                $admin->forceFill([
                    'name' => $admin->name ?: env('ADMIN_NAME', 'Super Admin'),
                ]);
            }

            if (Schema::hasColumn('users', 'status')) {
                $admin->status = 1;
            }

            $admin->save();
            $admin->userRole()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}
