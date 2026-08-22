<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\CarRental;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarRentalController extends Controller
{
    private const CAR_RENTAL_PER_PAGE = 12;

    public function index(Request $request)
    {
        $validated = $request->validate([
            'key_car' => 'nullable|string|max:191',
            'location_id' => 'nullable|integer',
            'seats' => 'nullable|integer|min:1|max:60',
            'vehicle_type' => ['nullable', 'string', Rule::in(array_keys(CarRental::VEHICLE_TYPES))],
            'driver_option' => ['nullable', 'string', Rule::in(array_keys(CarRental::DRIVER_OPTIONS))],
        ]);

        $carRentals = CarRental::with('location');

        if (!empty($validated['key_car'])) {
            $carRentals->where('cr_name', 'like', '%' . $validated['key_car'] . '%');
        }

        if (!empty($validated['location_id'])) {
            $carRentals->where('cr_location_id', $validated['location_id']);
        }

        if (!empty($validated['seats'])) {
            $carRentals->where('cr_number_seats', (int) $validated['seats']);
        }

        if (!empty($validated['vehicle_type'])) {
            $carRentals->where('cr_vehicle_type', $validated['vehicle_type']);
        }

        if (!empty($validated['driver_option'])) {
            $carRentals->where(function ($query) use ($validated) {
                $query->where('cr_driver_option', $validated['driver_option'])
                    ->orWhere('cr_driver_option', 'both');
            });
        }

        $carRentals = $carRentals->active()
            ->orderByDesc('id')
            ->paginate(self::CAR_RENTAL_PER_PAGE)
            ->withQueryString();
        $locations = Location::where('l_status', 1)->get();

        return view('page.car_rental.index', compact('carRentals', 'locations'));
    }

    public function detail($id)
    {
        $carRental = CarRental::with('location')->active()->find($id);
        if (!$carRental) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $relatedCars = CarRental::with('location')
            ->where('id', '<>', $id)
            ->active()
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('page.car_rental.detail', compact('carRental', 'relatedCars'));
    }
}
