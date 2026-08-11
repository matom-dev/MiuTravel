<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class AdminTourManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_tour_form_focuses_on_program_details_without_fixed_dates(): void
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        $html = View::make('admin.tour.form', [
            'locations' => collect([$location]),
            'status' => Tour::STATUS,
            'tourLeaders' => new Collection(),
            'tourGuideStaff' => new Collection(),
            'errors' => (new ViewErrorBag())->put('default', new MessageBag()),
        ])->render();

        $this->assertStringContainsString('Tên tour', $html);
        $this->assertStringContainsString('Hành trình', $html);
        $this->assertStringContainsString('Số ngày', $html);
        $this->assertStringContainsString('Số đêm', $html);
        $this->assertStringContainsString('Giá người lớn', $html);
        $this->assertStringContainsString('Giá trẻ em', $html);
        $this->assertStringContainsString('Khuyến mãi', $html);
        $this->assertStringContainsString('Nội dung tour', $html);
        $this->assertStringContainsString('Lịch trình chi tiết', $html);
        $this->assertStringContainsString('Hướng dẫn viên phụ trách', $html);
        $this->assertStringContainsString('Ảnh đại diện', $html);
        $this->assertStringContainsString('Album ảnh', $html);
        $this->assertStringContainsString('tour-form-sidebar', $html);
        $this->assertStringNotContainsString('Admin chỉ tạo thông tin tour', $html);
        $this->assertStringNotContainsString('Còn nhận đặt: khách thấy', $html);
        $this->assertStringNotContainsString('Hệ thống sẽ hiển thị thời gian tour', $html);
        $this->assertStringNotContainsString('name="t_start_date"', $html);
        $this->assertStringNotContainsString('name="t_end_date"', $html);
    }

    public function test_admin_tour_save_ignores_fixed_start_and_end_dates(): void
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        $request = Request::create('/admin/tour/create', 'POST', [
            't_title' => 'Tour admin linh hoat',
            't_location_id' => $location->id,
            't_status' => 1,
            't_price_adults' => 2000000,
            't_price_children' => 1000000,
            't_sale' => 5,
            't_move_method' => 'Ô tô du lịch',
            't_starting_gate' => 'Đồng Hới',
            't_journeys' => 'Đồng Hới - Phong Nha',
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_content' => '<p>Nội dung tour</p>',
            't_description' => '<p>Lịch trình chi tiết</p>',
            't_start_date' => '2026-09-10',
            't_end_date' => '2026-09-12',
        ]);

        $tour = (new Tour())->createOrUpdate($request);

        $this->assertSame('3 ngày 2 đêm', $tour->t_schedule);
        $this->assertNull($tour->t_start_date);
        $this->assertNull($tour->t_end_date);
    }
}
