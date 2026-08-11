<?php

namespace Tests\Feature;

use App\Http\Requests\HotelRequest;
use App\Models\Hotel;
use App\Models\Location;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class HotelDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotel_search_uses_destination_and_stay_context_without_prices(): void
    {
        $quangBinh = $this->createLocation('Quang Binh');
        $daNang = $this->createLocation('Da Nang');

        $matchingHotel = $this->createHotel($quangBinh, [
            'h_name' => 'Khach san Dong Hoi',
            'h_address' => 'Bao Ninh, Quang Binh',
        ]);
        $this->createHotel($daNang, [
            'h_name' => 'Khach san song Han',
            'h_address' => 'Hai Chau, Da Nang',
        ]);

        $checkIn = now()->addDays(5)->toDateString();
        $checkOut = now()->addDays(7)->toDateString();

        $response = $this->get(route('hotel', [
            'destination' => 'Quang Binh',
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'adults' => 2,
            'children' => 1,
            'rooms' => 1,
        ]));

        $response
            ->assertOk()
            ->assertSee($matchingHotel->h_name)
            ->assertDontSee('Khach san song Han')
            ->assertSee('name="check_in"', false)
            ->assertSee('name="check_out"', false)
            ->assertSee('name="adults"', false)
            ->assertSee('name="rooms"', false)
            ->assertDontSee('giá và tình trạng phòng do khách sạn xác nhận trực tiếp')
            ->assertDontSee('Giá và phòng trống do khách sạn xác nhận')
            ->assertDontSee('Khoảng giá')
            ->assertDontSee('/đêm');
    }

    public function test_hotel_detail_connects_directly_to_reception_without_commercial_claims(): void
    {
        $location = $this->createLocation('Quang Binh');
        $hotel = $this->createHotel($location, [
            'h_name' => 'Khach san Nhat Le',
            'h_phone' => '0232 388 9999',
        ]);

        $checkIn = now()->addDays(5)->toDateString();
        $checkOut = now()->addDays(7)->toDateString();

        $this->get(route('hotel.detail', [
            'id' => $hotel->id,
            'slug' => 'khach-san-nhat-le',
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'adults' => 2,
            'children' => 1,
            'rooms' => 1,
        ]))
            ->assertOk()
            ->assertSee('Gọi trực tiếp lễ tân')
            ->assertSee('tel:02323889999', false)
            ->assertSee('Nhu cầu lưu trú của bạn')
            ->assertDontSee('Miu Travel chỉ cung cấp thông tin kết nối')
            ->assertDontSee('Giá chỉ từ')
            ->assertDontSee('Còn phòng trống')
            ->assertDontSee('Miễn phí hủy phòng')
            ->assertDontSee('Giá tốt nhất')
            ->assertDontSee('(4.5/5)');
    }

    public function test_hotel_directory_filters_by_verified_metadata(): void
    {
        $location = $this->createLocation('Quang Binh');
        $matchingHotel = $this->createHotel($location, [
            'h_name' => 'Bien Xanh Resort',
            'h_accommodation_type' => 'resort',
            'h_star_rating' => 5,
            'h_amenities' => ['near_beach', 'pool', 'wifi'],
            'h_suitable_for' => ['family', 'couple'],
        ]);
        $this->createHotel($location, [
            'h_name' => 'Khach san Trung Tam',
            'h_accommodation_type' => 'hotel',
            'h_star_rating' => 3,
            'h_amenities' => ['wifi', 'parking'],
            'h_suitable_for' => ['business'],
        ]);

        $this->get(route('hotel', [
            'types' => ['resort'],
            'stars' => [5],
            'amenities' => ['pool', 'wifi'],
            'suitable_for' => ['family'],
        ]))
            ->assertOk()
            ->assertSee($matchingHotel->h_name)
            ->assertDontSee('Khach san Trung Tam')
            ->assertSee('Bộ lọc khách sạn')
            ->assertSee('Tiện nghi phổ biến')
            ->assertSee('Phù hợp với')
            ->assertSee('hotel-card__verified-amenities', false)
            ->assertSee('name="amenities[]"', false)
            ->assertSee('id="hotel-filter-form"', false)
            ->assertDontSee('Áp dụng bộ lọc');
    }

    public function test_hotel_directory_displays_nine_cards_per_page(): void
    {
        $location = $this->createLocation('Quang Binh');

        foreach (range(1, 10) as $number) {
            $this->createHotel($location, ['h_name' => 'Khach san '.$number]);
        }

        $response = $this->get(route('hotel'));
        $hotels = $response->viewData('hotels');

        $response->assertOk();
        $this->assertSame(9, $hotels->count());
        $this->assertSame(9, $hotels->perPage());
        $this->assertSame(10, $hotels->total());
    }

    public function test_admin_hotel_form_has_no_price_and_requires_phone_for_published_hotels(): void
    {
        $location = $this->createLocation('Quang Binh');

        $html = View::make('admin.hotel.form', [
            'locations' => collect([$location]),
            'status' => Hotel::STATUS,
            'errors' => (new ViewErrorBag())->put('default', new MessageBag()),
        ])->render();

        $this->assertStringContainsString('Số điện thoại lễ tân', $html);
        $this->assertStringContainsString('Địa chỉ chi tiết', $html);
        $this->assertStringContainsString('name="h_address"', $html);
        $this->assertStringNotContainsString('name="h_location_id"', $html);
        $this->assertStringContainsString('name="h_accommodation_type"', $html);
        $this->assertStringContainsString('name="h_amenities[]"', $html);
        $this->assertStringContainsString('name="h_suitable_for[]"', $html);
        $this->assertStringNotContainsString('name="h_price"', $html);
        $this->assertSame(1, substr_count($html, 'name="h_content"'));
        $this->assertStringNotContainsString('name="h_description"', $html);
        $this->assertStringNotContainsString('Hệ thống sẽ tự tạo đoạn tóm tắt', $html);

        $validator = Validator::make([
            'h_name' => 'Khach san test',
            'h_address' => '20 Quach Xuan Ky, Dong Hoi',
            'h_status' => 1,
        ], (new HotelRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('h_phone', $validator->errors()->toArray());
        $this->assertArrayHasKey('h_content', $validator->errors()->toArray());

        $request = Request::create('/admin/hotel/create', 'POST', [
            'h_name' => 'Khach san mot trinh soan thao',
            'h_address' => '20 Quach Xuan Ky, Dong Hoi',
            'h_phone' => '0901234567',
            'h_accommodation_type' => 'hotel',
            'h_status' => 1,
            'h_content' => '<h2>Vi tri thuan tien</h2><p>Khach san co tien nghi phu hop cho gia dinh va nhom ban.</p>',
        ]);

        $hotel = (new Hotel())->createOrUpdate($request);

        $this->assertStringContainsString('Vi tri thuan tien', $hotel->h_description);
        $this->assertSame($hotel->h_description, strip_tags($hotel->h_description));
        $this->assertStringContainsString('<h2>Vi tri thuan tien</h2>', $hotel->h_content);
    }

    public function test_hotel_commercial_fields_migration_can_run_and_roll_back(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->integer('h_price')->nullable()->default(0);
            $table->tinyInteger('h_price_contact')->nullable()->default(0);
            $table->integer('h_sale')->default(0);
        });

        require_once database_path('migrations/2026_08_08_000001_remove_commercial_fields_from_hotels_table.php');
        $migration = new \RemoveCommercialFieldsFromHotelsTable();
        $migration->up();

        foreach (['h_price', 'h_price_contact', 'h_sale'] as $column) {
            $this->assertFalse(Schema::hasColumn('hotels', $column));
        }

        $migration->down();

        foreach (['h_price', 'h_price_contact', 'h_sale'] as $column) {
            $this->assertTrue(Schema::hasColumn('hotels', $column));
        }
    }

    private function createLocation(string $name): Location
    {
        return Location::create([
            'l_name' => $name,
            'l_slug' => safeTitle($name),
            'l_status' => 1,
        ]);
    }

    private function createHotel(Location $location, array $attributes = []): Hotel
    {
        return Hotel::create(array_merge([
            'h_name' => 'Khach san test',
            'h_address' => 'Dia chi test',
            'h_phone' => '0900000000',
            'h_accommodation_type' => 'hotel',
            'h_star_rating' => null,
            'h_amenities' => [],
            'h_suitable_for' => [],
            'h_description' => '<p>Thong tin luu tru.</p>',
            'h_content' => '<p>Noi dung khach san.</p>',
            'h_status' => 1,
            'h_location_id' => $location->id,
        ], $attributes));
    }
}
