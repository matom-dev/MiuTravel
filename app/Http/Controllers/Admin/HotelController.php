<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Http\Requests\HotelRequest;

class HotelController extends Controller
{
    protected $hotel;
    /**
     * HomeController constructor.
     */
    public function __construct(Hotel $hotel)
    {
        view()->share([
            'hotel_active' => 'active',
            'status' => $hotel::STATUS,
        ]);

        $this->hotel = $hotel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $hotels = Hotel::query();

        if ($request->filled('h_name')) {
            $keyword = trim((string) $request->h_name);
            $hotels->where(function ($query) use ($keyword) {
                $query->where('h_name', 'like', '%'.$keyword.'%')
                    ->orWhere('h_address', 'like', '%'.$keyword.'%');
            });
        }

        if ($request->filled('h_status') && array_key_exists((int) $request->h_status, Hotel::STATUS)) {
            $hotels->where('h_status', (int) $request->h_status);
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $hotels->orderBy('id');
        } elseif ($sort === 'name_asc') {
            $hotels->orderBy('h_name');
        } elseif ($sort === 'name_desc') {
            $hotels->orderByDesc('h_name');
        } else {
            $hotels->orderByDesc('id');
        }

        $hotels = $hotels->paginate(NUMBER_PAGINATION)->withQueryString();
        return view('admin.hotel.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.hotel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HotelRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            $this->hotel->createOrUpdate($request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $hotel = Hotel::findOrFail($id);

        if (!$hotel) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        return view('admin.hotel.edit', compact('hotel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HotelRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $this->hotel->createOrUpdate($request, $id);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    /**
     * Xóa một ảnh khỏi album khách sạn
     *
     * @param  int  $id
     * @param  int  $index
     * @return \Illuminate\Http\Response
     */
    public function removeAlbumImage($id, $index)
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $album = $hotel->h_anbum_image ? $hotel->h_anbum_image : [];
        if (isset($album[$index])) {
            $removedImage = $album[$index];
            array_splice($album, $index, 1);
            $hotel->h_anbum_image = array_values($album);
            $hotel->save();
            delete_uploaded_image($removedImage);
        }

        return redirect()->back()->with('success', 'Đã xóa ảnh khỏi album');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        try {
            $hotel->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }
}
