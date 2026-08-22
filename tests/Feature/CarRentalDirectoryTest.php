<?php

namespace Tests\Feature;

use App\Http\Requests\CarRentalRequest;
use App\Models\CarRental;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class CarRentalDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_car_rental_directory_uses_direct_contact_instead_of_prices(): void
    {
        $carRental = $this->createCarRental();

        $this->get(route('car.rental'))
            ->assertOk()
            ->assertSee('Thuê xe du lịch')
            ->assertSee($carRental->cr_name)
            ->assertSee('Liên hệ trực tiếp')
            ->assertDontSee('Giá thuê từ')
            ->assertDontSee('/ngày');

        $this->get(route('car.rental.detail', [
            'id' => $carRental->id,
            'slug' => safeTitle($carRental->cr_name),
        ]))
            ->assertOk()
            ->assertSee('Nhận báo giá từ đơn vị cho thuê')
            ->assertSee('Gọi trực tiếp đơn vị')
            ->assertSee('tel:0901234567', false)
            ->assertDontSee('Miu Travel chỉ cung cấp thông tin kết nối')
            ->assertDontSee('Giá thuê từ');
    }

    public function test_admin_car_rental_form_has_no_price_and_requires_phone_when_published(): void
    {
        $location = $this->createLocation();
        $html = View::make('admin.car_rental.form', [
            'locations' => collect([$location]),
            'status' => CarRental::STATUS,
            'errors' => (new ViewErrorBag())->put('default', new MessageBag()),
        ])->render();

        $this->assertStringContainsString('Điện thoại đơn vị cho thuê', $html);
        $this->assertStringContainsString('name="cr_phone"', $html);
        $this->assertStringContainsString('name="cr_driver_option"', $html);
        $this->assertStringNotContainsString('name="cr_price"', $html);
        $this->assertSame(1, substr_count($html, 'name="cr_content"'));
        $this->assertStringNotContainsString('name="cr_description"', $html);
        $this->assertStringNotContainsString('Hệ thống sẽ tự tạo đoạn tóm tắt', $html);

        $publishedValidator = Validator::make([
            'cr_name' => 'Xe du lich test',
            'cr_status' => 1,
        ], (new CarRentalRequest())->rules());

        $this->assertTrue($publishedValidator->fails());
        $this->assertArrayHasKey('cr_phone', $publishedValidator->errors()->toArray());
        $this->assertArrayHasKey('cr_content', $publishedValidator->errors()->toArray());

        $draftValidator = Validator::make([
            'cr_name' => 'Xe du lich test',
            'cr_status' => 2,
        ], (new CarRentalRequest())->rules());

        $this->assertFalse($draftValidator->fails());

        $request = Request::create('/admin/car-rental/create', 'POST', [
            'cr_name' => 'Xe 7 cho mot trinh soan thao',
            'cr_status' => 1,
            'cr_phone' => '0901234567',
            'cr_content' => '<h2>Don tan noi</h2><p>Dich vu phuc vu tai Dong Hoi va Phong Nha theo lich hen.</p>',
        ]);

        $carRental = (new CarRental())->createOrUpdate($request);

        $this->assertStringContainsString('Don tan noi', $carRental->cr_description);
        $this->assertSame($carRental->cr_description, strip_tags($carRental->cr_description));
        $this->assertStringContainsString('<h2>Don tan noi</h2>', $carRental->cr_content);
    }

    public function test_car_rental_directory_filters_by_vehicle_metadata(): void
    {
        $quangBinh = $this->createLocation();
        $daNang = Location::create([
            'l_name' => 'Da Nang',
            'l_slug' => 'da-nang',
            'l_status' => 1,
        ]);

        $matchingCar = CarRental::create([
            'cr_name' => 'SUV 7 cho co tai xe',
            'cr_location_id' => $quangBinh->id,
            'cr_vehicle_type' => 'suv',
            'cr_driver_option' => 'with_driver',
            'cr_number_seats' => 7,
            'cr_phone' => '0901 111 222',
            'cr_status' => 1,
        ]);
        CarRental::create([
            'cr_name' => 'Sedan tu lai Da Nang',
            'cr_location_id' => $daNang->id,
            'cr_vehicle_type' => 'sedan',
            'cr_driver_option' => 'self_drive',
            'cr_number_seats' => 4,
            'cr_phone' => '0901 333 444',
            'cr_status' => 1,
        ]);

        $this->get(route('car.rental', [
            'location_id' => $quangBinh->id,
            'seats' => 7,
            'vehicle_type' => 'suv',
            'driver_option' => 'with_driver',
        ]))
            ->assertOk()
            ->assertSee($matchingCar->cr_name)
            ->assertSee('Có tài xế')
            ->assertDontSee('Sedan tu lai Da Nang');
    }

    public function test_car_rental_directory_displays_twelve_cars_per_page(): void
    {
        $location = $this->createLocation();

        foreach (range(1, 13) as $number) {
            CarRental::create([
                'cr_name' => 'Xe du lich '.$number,
                'cr_location_id' => $location->id,
                'cr_phone' => '0901234567',
                'cr_status' => 1,
            ]);
        }

        $response = $this->get(route('car.rental'));
        $carRentals = $response->viewData('carRentals');

        $response
            ->assertOk()
            ->assertSee('Xe du lich 13')
            ->assertSee('class="block-27', false);
        $this->assertSame(12, $carRentals->count());
        $this->assertSame(12, $carRentals->perPage());
        $this->assertSame(13, $carRentals->total());

        $secondPage = $this->get(route('car.rental', ['page' => 2]));
        $secondPageCars = $secondPage->viewData('carRentals');

        $secondPage->assertOk()->assertSee('Xe du lich 1');
        $this->assertCount(1, $secondPageCars);
    }

    private function createLocation(): Location
    {
        return Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);
    }

    private function createCarRental(): CarRental
    {
        return CarRental::create([
            'cr_name' => 'Xe 7 cho Phong Nha',
            'cr_location_id' => $this->createLocation()->id,
            'cr_vehicle_type' => 'SUV',
            'cr_number_seats' => 7,
            'cr_transmission' => 'Tự động',
            'cr_fuel' => 'Xăng',
            'cr_phone' => '0901 234 567',
            'cr_address' => 'Dong Hoi',
            'cr_description' => '<p>Dich vu xe du lich.</p>',
            'cr_status' => 1,
        ]);
    }
}
