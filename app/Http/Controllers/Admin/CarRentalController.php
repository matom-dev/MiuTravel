<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRentalRequest;
use App\Models\CarRental;
use App\Models\Location;

class CarRentalController extends Controller
{
    protected $carRental;

    public function __construct(CarRental $carRental, Location $location)
    {
        view()->share([
            'car_rental_active' => 'active',
            'status' => $carRental::STATUS,
        ]);

        view()->composer(['admin.car_rental.*'], function ($view) use ($location) {
            $view->with('locations', $location->where('l_status', 1)->get());
        });

        $this->carRental = $carRental;
    }

    public function index()
    {
        $carRentals = CarRental::with('location')->orderByDesc('id')->paginate(NUMBER_PAGINATION);
        return view('admin.car_rental.index', compact('carRentals'));
    }

    public function create()
    {
        return view('admin.car_rental.create');
    }

    public function store(CarRentalRequest $request)
    {
        \DB::beginTransaction();
        try {
            $this->carRental->createOrUpdate($request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function edit($id)
    {
        $carRental = CarRental::findOrFail($id);
        return view('admin.car_rental.edit', compact('carRental'));
    }

    public function update(CarRentalRequest $request, $id)
    {
        \DB::beginTransaction();
        try {
            $this->carRental->createOrUpdate($request, $id);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function removeAlbumImage($id, $index)
    {
        $carRental = CarRental::find($id);
        if (!$carRental) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $album = $carRental->cr_album_images ?: [];
        if (isset($album[$index])) {
            $removedImage = $album[$index];
            array_splice($album, $index, 1);
            $carRental->cr_album_images = array_values($album);
            $carRental->save();
            delete_uploaded_image($removedImage);
        }

        return redirect()->back()->with('success', 'Đã xóa ảnh khỏi album');
    }

    public function delete($id)
    {
        $carRental = CarRental::find($id);
        if (!$carRental) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        try {
            $carRental->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }
}
