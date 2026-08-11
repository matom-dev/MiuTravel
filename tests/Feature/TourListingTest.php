<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_tours_by_location_price_and_duration(): void
    {
        $quangBinh = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);
        $daNang = Location::create([
            'l_name' => 'Da Nang',
            'l_slug' => 'da-nang',
            'l_status' => 1,
        ]);

        $matchingTour = $this->createTour([
            't_title' => 'Tour Phong Nha 3 ngay',
            't_location_id' => $quangBinh->id,
            't_price_adults' => 2500000,
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_schedule' => '3 ngay 2 dem',
        ]);
        $this->createTour([
            't_title' => 'Tour Da Nang 3 ngay',
            't_location_id' => $daNang->id,
            't_price_adults' => 2500000,
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_schedule' => '3 ngay 2 dem',
        ]);
        $this->createTour([
            't_title' => 'Tour Phong Nha 1 ngay',
            't_location_id' => $quangBinh->id,
            't_price_adults' => 800000,
            't_duration_days' => 1,
            't_duration_nights' => 0,
            't_schedule' => '1 ngay',
        ]);

        $response = $this->get(route('tour', [
            'location_id' => $quangBinh->id,
            'price' => '2000000-3000000',
            'duration' => '2-3',
        ]));

        $response
            ->assertOk()
            ->assertSee($matchingTour->t_title)
            ->assertDontSee('Tour Da Nang 3 ngay')
            ->assertDontSee('Tour Phong Nha 1 ngay')
            ->assertSee('Còn nhận đặt')
            ->assertSee('Xem chi tiết')
            ->assertSee('Đặt tour');
    }

    public function test_paused_tours_are_visible_but_not_bookable_on_public_pages(): void
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        $pausedTour = $this->createTour([
            't_title' => 'Tour tam ngung nhan dat',
            't_location_id' => $location->id,
            't_status' => 2,
        ]);
        $hiddenTour = $this->createTour([
            't_title' => 'Tour ngung hien thi',
            't_location_id' => $location->id,
            't_status' => 3,
        ]);

        $this->get(route('tour'))
            ->assertOk()
            ->assertSee($pausedTour->t_title)
            ->assertSee('Tạm ngưng nhận đặt')
            ->assertSee('Tạm ngưng')
            ->assertDontSee($hiddenTour->t_title);

        $this->get(route('tour.detail', ['id' => $pausedTour->id, 'slug' => 'tour-tam-ngung-nhan-dat']))
            ->assertOk()
            ->assertSee($pausedTour->t_title)
            ->assertSee('Tạm ngưng nhận đặt')
            ->assertSee('chưa mở nhận booking mới')
            ->assertDontSee('Đặt Tour Ngay');

        $this->get(route('tour.detail', ['id' => $hiddenTour->id, 'slug' => 'tour-ngung-hien-thi']))
            ->assertStatus(302)
            ->assertSessionHas('error', 'Dữ liệu không tồn tại');
    }

    public function test_tour_detail_highlights_flexible_booking_sections(): void
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        $tour = $this->createTour([
            't_title' => 'Tour linh hoat 3 ngay',
            't_location_id' => $location->id,
            't_journeys' => 'Dong Hoi - Phong Nha - Thien Duong',
            't_schedule' => '3 ngày 2 đêm',
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_move_method' => 'Ô tô du lịch',
            't_starting_gate' => 'Đồng Hới',
            't_price_adults' => 2000000,
            't_price_children' => 1000000,
            't_content' => '<p>Tổng quan chương trình tour.</p>',
            't_description' => '<h3>Ngày 1</h3><p>Khởi hành theo ngày khách chọn.</p>',
            't_guides' => [
                [
                    'name' => 'Nguyen Van Guide',
                    'role' => 'Hướng dẫn viên',
                    'experience' => '5 năm kinh nghiệm',
                ],
            ],
        ]);

        $this->get(route('tour.detail', ['id' => $tour->id, 'slug' => 'tour-linh-hoat-3-ngay']))
            ->assertOk()
            ->assertSee($tour->t_title)
            ->assertSee('Tổng quan tour')
            ->assertSee('Hành trình')
            ->assertSee('Thời gian')
            ->assertSee('Phương tiện')
            ->assertSee('Điểm khởi hành')
            ->assertSee('Bảng giá theo nhóm tuổi')
            ->assertSee('Lịch trình chi tiết từng ngày')
            ->assertSee('Hướng dẫn viên phụ trách')
            ->assertSee('Album ảnh')
            ->assertSee('Đánh giá và bình luận')
            ->assertSee('Đặt tour theo ngày mong muốn')
            ->assertSee('Không áp ngày đi cố định')
            ->assertDontSee('Ngày đi cố định')
            ->assertDontSee('Ngày về cố định');
    }

    private function createTour(array $attributes = []): Tour
    {
        return Tour::create(array_merge([
            't_title' => 'Tour test',
            't_journeys' => 'Quang Binh',
            't_schedule' => '2 ngay 1 dem',
            't_duration_days' => 2,
            't_duration_nights' => 1,
            't_move_method' => 'Oto',
            't_starting_gate' => 'Dong Hoi',
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_sale' => 0,
            't_number_registered' => 0,
            't_follow' => 0,
            't_status' => 1,
        ], $attributes));
    }
}
