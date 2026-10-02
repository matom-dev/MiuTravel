<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Comment;
use App\Models\Location;
use App\Models\Tour;
use App\Models\User;
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
            't_type' => 'adventure',
            't_price_adults' => 2500000,
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_schedule' => '3 ngay 2 dem',
        ]);
        $this->createTour([
            't_title' => 'Tour Da Nang 3 ngay',
            't_location_id' => $daNang->id,
            't_type' => 'adventure',
            't_price_adults' => 2500000,
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_schedule' => '3 ngay 2 dem',
        ]);
        $this->createTour([
            't_title' => 'Tour Phong Nha 1 ngay',
            't_location_id' => $quangBinh->id,
            't_type' => 'family',
            't_price_adults' => 800000,
            't_duration_days' => 1,
            't_duration_nights' => 0,
            't_schedule' => '1 ngay',
        ]);

        $response = $this->get(route('tour', [
            'location_id' => $quangBinh->id,
            'price' => '2000000-3000000',
            'duration' => '2-3',
            'tour_type' => 'adventure',
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

    public function test_tour_detail_renders_itinerary_as_day_timeline(): void
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        $tour = $this->createTour([
            't_title' => 'Tour timeline Phong Nha',
            't_location_id' => $location->id,
            't_description' => '<h3>Ngày 1: Động Phong Nha</h3><p>Khởi hành và khám phá hang động.</p><h3>Ngày 2: Suối Moọc</h3><p>Tự do trải nghiệm sinh thái.</p>',
        ]);

        $this->get(route('tour.detail', ['id' => $tour->id, 'slug' => 'tour-timeline-phong-nha']))
            ->assertOk()
            ->assertSee('itinerary-timeline', false)
            ->assertSee('Ngày 1: Động Phong Nha')
            ->assertSee('Ngày 2: Suối Moọc');
    }

    public function test_tour_detail_shows_organizing_agency_without_private_verification_data(): void
    {
        $agency = Agency::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Đại lý A',
            'logo' => '2026-09-30__agency.png',
            'description' => 'Chuyên tour miền Trung',
            'address' => '12 Nguyễn Huệ, Đà Nẵng',
            'phone' => '090 123 4567',
            'email' => 'dailya@example.com',
            'verification_information' => 'Số giấy phép riêng tư 123',
        ]);
        $tour = $this->createTour(['t_title' => 'Tour của đại lý']);
        $tour->forceFill(['agency_id' => $agency->id])->save();

        $this->get(route('tour.detail', ['id' => $tour->id, 'slug' => 'tour-cua-dai-ly']))
            ->assertOk()
            ->assertSee('Đại lý tổ chức')
            ->assertSee('Đại lý A')
            ->assertSee('Chuyên tour miền Trung')
            ->assertSee('12 Nguyễn Huệ, Đà Nẵng')
            ->assertSee('tel:0901234567', false)
            ->assertSee('dailya@example.com')
            ->assertSee('Logo Đại lý A')
            ->assertDontSee('Số giấy phép riêng tư 123');

        $legacyTour = $this->createTour(['t_title' => 'Tour cũ']);
        $this->get(route('tour.detail', ['id' => $legacyTour->id, 'slug' => 'tour-cu']))
            ->assertOk()
            ->assertDontSee('Đại lý tổ chức');
    }

    public function test_customer_can_open_agency_profile_and_see_only_public_tour_totals(): void
    {
        $agency = Agency::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Đại lý Miền Trung',
            'description' => 'Tổ chức tour miền Trung',
            'address' => 'Đà Nẵng',
            'verification_information' => 'Giấy phép nội bộ bí mật',
        ]);
        $published = $this->createTour(['t_title' => 'Tour Đà Nẵng']);
        $published->forceFill(['agency_id' => $agency->id])->save();
        $paused = $this->createTour(['t_title' => 'Tour Huế', 't_status' => 2]);
        $paused->forceFill(['agency_id' => $agency->id])->save();
        $hidden = $this->createTour(['t_title' => 'Tour chưa công khai', 't_status' => 3]);
        $hidden->forceFill(['agency_id' => $agency->id])->save();
        $reviewer = User::factory()->create(['name' => 'Khách An']);
        Comment::create(['cm_tour_id' => $published->id, 'cm_user_id' => $reviewer->id, 'cm_content' => 'Chuyến đi rất tốt', 'cm_rating' => 5, 'cm_status' => Comment::STATUS_APPROVED]);
        Comment::create(['cm_tour_id' => $published->id, 'cm_user_id' => $reviewer->id, 'cm_content' => 'Hướng dẫn tận tình', 'cm_rating' => 4, 'cm_status' => Comment::STATUS_APPROVED]);
        Comment::create(['cm_tour_id' => $published->id, 'cm_user_id' => $reviewer->id, 'cm_content' => 'Bình luận chưa duyệt', 'cm_rating' => 1, 'cm_status' => Comment::STATUS_PENDING]);
        Comment::create(['cm_tour_id' => $hidden->id, 'cm_user_id' => $reviewer->id, 'cm_content' => 'Bình luận tour ẩn', 'cm_rating' => 5, 'cm_status' => Comment::STATUS_APPROVED]);

        $url = route('agency.public', $agency->id);
        $this->get(route('tour'))->assertOk()->assertSee($url, false);
        $this->get(route('tour.detail', ['id' => $published->id, 'slug' => 'tour-da-nang']))
            ->assertOk()->assertSee($url, false)->assertSee('Xem hồ sơ và các tour của đại lý');
        $this->get($url)->assertOk()
            ->assertSee('Đại lý Miền Trung')
            ->assertSee('Tour Đà Nẵng')
            ->assertSee('Tour Huế')
            ->assertSee('Hướng dẫn tận tình')
            ->assertSee('Khách An')
            ->assertSee('4,5/5')
            ->assertSee('2 đánh giá đã duyệt')
            ->assertSee('Chưa có đánh giá từ khách hàng.')
            ->assertSee('public-agency-list', false)
            ->assertDontSee('Đặt tour')
            ->assertDontSee('Bình luận chưa duyệt')
            ->assertDontSee('Bình luận tour ẩn')
            ->assertDontSee('Tour chưa công khai')
            ->assertDontSee('Giấy phép nội bộ bí mật')
            ->assertViewHas('tourCount', 2);

        $privateAgency = Agency::create(['user_id' => User::factory()->create()->id, 'name' => 'Chưa công khai']);
        $this->get(route('agency.public', $privateAgency->id))->assertNotFound();
    }

    public function test_newest_tour_is_displayed_first_within_the_same_status(): void
    {
        $olderTour = $this->createTour(['t_title' => 'Tour cu']);
        $newerTour = $this->createTour(['t_title' => 'Tour moi']);

        $response = $this->get(route('tour'));
        $tours = $response->viewData('tours');

        $response->assertOk();
        $this->assertSame([$newerTour->id, $olderTour->id], $tours->pluck('id')->take(2)->all());
    }

    public function test_tour_directory_displays_sixteen_tours_per_page(): void
    {
        foreach (range(1, 17) as $number) {
            $this->createTour(['t_title' => 'Tour phan trang '.$number]);
        }

        $response = $this->get(route('tour'));
        $tours = $response->viewData('tours');

        $response->assertOk();
        $this->assertSame(16, $tours->count());
        $this->assertSame(16, $tours->perPage());
        $this->assertSame(17, $tours->total());
        $this->assertSame('Tour phan trang 17', $tours->first()->t_title);

        $secondPage = $this->get(route('tour', ['page' => 2]));
        $secondPageTours = $secondPage->viewData('tours');

        $secondPage->assertOk();
        $this->assertCount(1, $secondPageTours);
        $this->assertSame('Tour phan trang 1', $secondPageTours->first()->t_title);
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
