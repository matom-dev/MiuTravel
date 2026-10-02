<?php

namespace App\Console\Commands;

use App\Models\Agency;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class CreateAgency extends Command
{
    protected $signature = 'agency:create {email} {name} {--commission=0 : Hoa hồng phần trăm, từ 0 đến 100}';

    protected $description = 'Cấp cổng đại lý cho một tài khoản hiện có';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();
        $rate = $this->option('commission');
        if (! $user || ! is_numeric($rate) || $rate < 0 || $rate > 100) {
            $this->error('Tài khoản không tồn tại hoặc hoa hồng không hợp lệ.');

            return self::FAILURE;
        }
        if (Agency::where('user_id', $user->id)->exists()) {
            $this->error('Tài khoản đã có đại lý.');

            return self::FAILURE;
        }
        $role = Role::where('name', 'dai-ly-du-lich')->first();
        if (! $role) {
            $this->error('Chưa có vai trò đại lý. Vui lòng chạy migration trước.');

            return self::FAILURE;
        }
        \DB::transaction(function () use ($user, $role, $rate) {
            Agency::create(['user_id' => $user->id, 'name' => $this->argument('name'), 'email' => $user->email, 'commission_basis_points' => (int) round($rate * 100)]);
            $user->userRole()->syncWithoutDetaching([$role->id]);
        });
        $this->info('Đã cấp quyền đại lý. Đăng nhập tại /admin/login và mở /admin/agency.');

        return self::SUCCESS;
    }
}
