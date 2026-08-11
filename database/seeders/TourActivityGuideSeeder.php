<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;

class TourActivityGuideSeeder extends Seeder
{
    public function run()
    {
        $defaultActivities = [
            [
                'title' => 'Dã ngoại và trekking nhẹ',
                'icon' => 'fa fa-tree',
                'description' => 'Khám phá thiên nhiên, đi bộ nhẹ theo cung đường an toàn và dừng chân tại các điểm ngắm cảnh đẹp.',
            ],
            [
                'title' => 'Hoạt động thể thao ngoài trời',
                'icon' => 'fa fa-futbol-o',
                'description' => 'Tham gia trò chơi nhóm, chèo thuyền hoặc vận động ngoài trời tùy lịch trình từng tour.',
            ],
            [
                'title' => 'Trải nghiệm văn hóa địa phương',
                'icon' => 'fa fa-university',
                'description' => 'Tìm hiểu làng nghề, phong tục, câu chuyện bản địa và những nét văn hóa đặc trưng của điểm đến.',
            ],
            [
                'title' => 'Ẩm thực vùng miền',
                'icon' => 'fa fa-cutlery',
                'description' => 'Thưởng thức món ăn đặc sản, gợi ý quán địa phương và trải nghiệm bữa ăn theo lịch trình.',
            ],
        ];

        $defaultGuides = [
            [
                'name' => 'Nguyễn Minh Anh',
                'role' => 'Trưởng đoàn',
                'phone' => '0901 234 567',
                'email' => 'minhanh@miutravel.vn',
                'experience' => '6 năm dẫn tour trải nghiệm tại Việt Nam',
                'languages' => 'Tiếng Việt, tiếng Anh',
            ],
            [
                'name' => 'Trần Quốc Bảo',
                'role' => 'Hướng dẫn viên địa phương',
                'phone' => '0912 456 789',
                'email' => 'quocbao@miutravel.vn',
                'experience' => '5 năm phụ trách tour văn hóa và thiên nhiên',
                'languages' => 'Tiếng Việt',
            ],
        ];

        Tour::query()->chunkById(50, function ($tours) use ($defaultActivities, $defaultGuides) {
            foreach ($tours as $tour) {
                $dirty = false;

                if (empty($tour->t_activities)) {
                    $tour->t_activities = $defaultActivities;
                    $dirty = true;
                }

                if (empty($tour->t_guides)) {
                    $tour->t_guides = $defaultGuides;
                    $dirty = true;
                }

                if ($dirty) {
                    $tour->save();
                }
            }
        });
    }
}
