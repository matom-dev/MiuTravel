<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\CarRental;
use App\Models\Location;
use Illuminate\Http\Request;

class CarRentalController extends Controller
{
    private const CAR_RENTAL_PER_PAGE = 8;

    public function index(Request $request)
    {
        $carRentals = CarRental::with('location');

        if ($request->key_car) {
            $carRentals->where('cr_name', 'like', '%' . $request->key_car . '%');
        }

        if ($request->location_id) {
            $carRentals->where('cr_location_id', $request->location_id);
        }

        if ($request->seats) {
            $carRentals->where('cr_number_seats', (int) $request->seats);
        }

        $carRentals = $carRentals->active()->orderByDesc('id')->paginate(self::CAR_RENTAL_PER_PAGE);
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
