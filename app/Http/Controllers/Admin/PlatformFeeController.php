<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformFeeSetting;
use App\Services\AdminAuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlatformFeeController extends Controller
{
    public function report(Request $request)
    {
        $filters = $request->validate([
            'month'=>'nullable|integer|between:1,12',
            'year'=>'nullable|integer|between:2000,2100',
            'agency_id'=>'nullable|integer|exists:agencies,id',
        ]);
        $year = (int) ($filters['year'] ?? now()->year);
        $month = $request->has('month') ? ($filters['month'] ?? null) : now()->month;
        $agencyId = $filters['agency_id'] ?? null;
        $query = \App\Models\BookTour::whereNotNull('agency_id')
            ->where('b_status', \App\Models\BookTour::STATUS_COMPLETED)
            ->whereNotNull('revenue_recognized_at')->whereYear('revenue_recognized_at', $year)
            ->when($month, fn ($q) => $q->whereMonth('revenue_recognized_at', $month))
            ->when($agencyId, fn ($q) => $q->where('agency_id', $agencyId));
        $groups = [];
        foreach ((clone $query)->cursor() as $booking) {
            $id = $booking->agency_id;
            $groups[$id] ??= ['agency_id'=>$id, 'count'=>0, 'revenue'=>0, 'fee'=>0, 'net'=>0];
            $price = $booking->total_price;
            $fee = round($price * $booking->commission_basis_points / 10000);
            $groups[$id]['count']++;
            $groups[$id]['revenue'] += $price;
            $groups[$id]['fee'] += $fee;
            $groups[$id]['net'] += $price - $fee;
        }
        $groups = collect($groups)->sortByDesc('fee');
        $totals = collect(['count', 'revenue', 'fee', 'net'])->mapWithKeys(fn ($key) => [$key=>$groups->sum($key)])->all();
        $agencies = \App\Models\Agency::orderBy('name')->get()->keyBy('id');
        $bookings = $query->with(['agency', 'tour'])->orderByDesc('revenue_recognized_at')->orderByDesc('id')->paginate(20)->withQueryString();
        return view('admin.platform-fee-report', compact('year', 'month', 'agencyId', 'groups', 'totals', 'agencies', 'bookings'));
    }

    public function edit()
    {
        return view('admin.platform-fee', ['percentage'=>PlatformFeeSetting::currentBasisPoints() / 100]);
    }

    public function update(Request $request, AdminAuditLogger $audit)
    {
        $data = $request->validate(['percentage'=>['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/']]);
        DB::transaction(function () use ($data, $request, $audit) {
            $setting = PlatformFeeSetting::whereKey(1)->lockForUpdate()->firstOrFail();
            $old = $setting->basis_points;
            $setting->basis_points = (int) round((float) $data['percentage'] * 100);
            $setting->save();
            $audit->log('platform_fee.updated', $setting, ['old_basis_points'=>$old, 'new_basis_points'=>$setting->basis_points], $request);
        });
        return back()->with('success', 'Đã cập nhật phí chung. Chỉ áp dụng cho đơn mới; đơn cũ giữ nguyên tỷ lệ.');
    }
}
