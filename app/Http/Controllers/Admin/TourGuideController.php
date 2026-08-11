<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourGuideRequest;
use App\Models\TourGuide;
use Illuminate\Http\Request;

class TourGuideController extends Controller
{
    protected $tourGuide;

    public function __construct(TourGuide $tourGuide)
    {
        view()->share([
            'tour_guide_active' => 'active',
            'roles' => TourGuide::ROLES,
            'genders' => TourGuide::GENDERS,
            'status' => TourGuide::STATUS,
        ]);

        $this->tourGuide = $tourGuide;
    }

    public function index(Request $request)
    {
        $guides = TourGuide::query();

        if ($request->tg_name) {
            $guides->where('tg_name', 'like', '%' . $request->tg_name . '%');
        }

        if ($request->tg_role) {
            $guides->where('tg_role', $request->tg_role);
        }

        $guides = $guides->orderByDesc('id')->paginate(NUMBER_PAGINATION);

        return view('admin.tour_guide.index', compact('guides'));
    }

    public function create()
    {
        return view('admin.tour_guide.create');
    }

    public function store(TourGuideRequest $request)
    {
        \DB::beginTransaction();
        try {
            $this->tourGuide->createOrUpdate($request);
            \DB::commit();

            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function edit($id)
    {
        $guide = TourGuide::findOrFail($id);

        return view('admin.tour_guide.edit', compact('guide'));
    }

    public function update(TourGuideRequest $request, $id)
    {
        \DB::beginTransaction();
        try {
            $this->tourGuide->createOrUpdate($request, $id);
            \DB::commit();

            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function delete($id)
    {
        $guide = TourGuide::find($id);
        if (!$guide) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        try {
            if ($guide->tours()->exists()) {
                return redirect()->back()->with('error', 'Nhân sự này đang được gán vào tour, không thể xóa');
            }

            $guide->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }
}
