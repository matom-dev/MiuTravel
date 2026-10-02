<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyTransaction;
use App\Models\BookingPassenger;
use App\Models\BookTour;
use App\Models\Location;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\VnpayPayment;
use App\Services\BookingService;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class PortalController extends Controller
{
    private function tours(Request $r)
    {
        return Tour::where('agency_id', $r->attributes->get('agency')->id);
    }

    private function bookings(Request $r)
    {
        return BookTour::whereIn('b_tour_id', $this->tours($r)->select('id'));
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['agency' => $message]);
    }

    public function index(Request $r)
    {
        $filters = $r->validate(['q' => 'nullable|string|max:100', 'tour_id' => 'nullable|integer', 'schedule_id' => 'nullable|integer', 'status' => 'nullable|integer|between:1,5', 'from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from', 'month' => 'nullable|integer|between:1,12', 'year' => 'nullable|integer|between:2000,2100']);
        $agency = $r->attributes->get('agency');
        $tours = $this->tours($r)->with('schedules')->latest()->get();
        $query = $this->bookings($r);
        if ($r->filled('tour_id')) {
            $query->where('b_tour_id', $filters['tour_id']);
        }
        if ($r->filled('schedule_id')) {
            $query->where('b_tour_schedule_id', $filters['schedule_id']);
        }
        if ($r->filled('status')) {
            $query->where('b_status', $filters['status']);
        }
        if ($r->filled('q')) {
            $query->where(function ($q) use ($filters) {
                foreach (['b_code', 'b_name', 'b_email', 'b_phone'] as $field) {
                    $q->orWhere($field, 'like', '%'.$filters['q'].'%');
                }
            });
        }
        $dated = function ($q, $column) use ($r, $filters) {
            if ($r->filled('from')) {
                $q->whereDate($column, '>=', $filters['from']);
            }
            if ($r->filled('to')) {
                $q->whereDate($column, '<=', $filters['to']);
            }
            if ($r->filled('year')) {
                $q->whereYear($column, $filters['year']);
            }
            if ($r->filled('month')) {
                $q->whereMonth($column, $filters['month']);
            }

            return $q;
        };
        $rows = $dated(clone $query, 'created_at')->get();
        $ids = (clone $query)->select('id');
        $transactions = $dated(AgencyTransaction::whereIn('book_tour_id', clone $ids), 'occurred_at')->get();
        $online = $dated(VnpayPayment::whereIn('book_tour_id', clone $ids)->where('status', 'paid'), 'updated_at')->sum('amount');
        $recognized = $dated((clone $query)->whereNotNull('revenue_recognized_at'), 'revenue_recognized_at')->get();
        $revenue = $recognized->sum(fn ($b) => $b->total_price);
        $commission = $recognized->sum(fn ($b) => round($b->total_price * $b->commission_basis_points / 10000));
        $metrics = ['Lượt đăng ký' => $rows->count(), 'Hành khách' => $rows->sum('total_guests'), 'Chờ xử lý' => $rows->where('b_status', 1)->count(), 'Đã xác nhận' => $rows->whereIn('b_status', [2, 3, 4])->count(), 'Đã hủy' => $rows->where('b_status', 5)->count(), 'Giá trị đặt tour' => $rows->where('b_status', '!=', 5)->sum('total_price'), 'Tiền đã thu' => $transactions->where('type', 'receipt')->sum('amount') + $online, 'Tiền đã hoàn' => $transactions->where('type', 'refund')->sum('amount'), 'Doanh thu ghi nhận' => $revenue, 'Phí nền tảng' => $commission, 'Đại lý được hưởng' => $revenue - $commission];
        $metrics['Thực thu ròng'] = $metrics['Tiền đã thu'] - $metrics['Tiền đã hoàn'];
        return view('admin.agency.reports', compact('agency', 'tours', 'metrics'));
    }

    public function profilePage(Request $r)
    {
        return view('admin.agency.profile', ['agency' => $r->attributes->get('agency')]);
    }

    public function toursPage(Request $r)
    {
        $agency = $r->attributes->get('agency');
        $tours = $this->tours($r)->with('schedules')->latest()->get();
        $occupancy = $this->bookings($r)->where('b_status', '!=', BookTour::STATUS_CANCELLED)
            ->get()->groupBy('b_tour_schedule_id')->map(fn ($items) => $items->sum('total_guests'));

        return view('admin.agency.tours', compact('agency', 'tours', 'occupancy'));
    }

    public function bookingsPage(Request $r)
    {
        $filters = $r->validate([
            'q' => 'nullable|string|max:100',
            'tour_id' => 'nullable|integer',
            'schedule_id' => 'nullable|integer',
            'status' => 'nullable|integer|between:1,5',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);
        $agency = $r->attributes->get('agency');
        $tours = $this->tours($r)->with('schedules')->latest()->get();
        $query = $this->bookings($r)->with('tour', 'schedule');
        if ($r->filled('tour_id')) {
            $query->where('b_tour_id', $filters['tour_id']);
        }
        if ($r->filled('schedule_id')) {
            $query->where('b_tour_schedule_id', $filters['schedule_id']);
        }
        if ($r->filled('status')) {
            $query->where('b_status', $filters['status']);
        }
        if ($r->filled('q')) {
            $query->where(function ($q) use ($filters) {
                foreach (['b_code', 'b_name', 'b_email', 'b_phone'] as $field) {
                    $q->orWhere($field, 'like', '%'.$filters['q'].'%');
                }
            });
        }
        if ($r->filled('from')) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }
        if ($r->filled('to')) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }
        $bookings = $query->latest()->paginate(20)->withQueryString();
        $bookingIds = $bookings->pluck('id');
        $passengers = BookingPassenger::whereIn('book_tour_id', $bookingIds)->get()->groupBy('book_tour_id');
        $ledger = AgencyTransaction::whereIn('book_tour_id', $bookingIds)->orderByDesc('occurred_at')->get()->groupBy('book_tour_id');
        $payments = VnpayPayment::whereIn('book_tour_id', $bookingIds)->whereIn('status', ['paid', 'review'])->latest()->get()->groupBy('book_tour_id');

        return view('admin.agency.bookings', compact('agency', 'tours', 'bookings', 'passengers', 'ledger', 'payments'));
    }

    public function profile(Request $r)
    {
        $data = $r->validate(['name' => 'required|string|max:255', 'description' => 'nullable|string|max:10000', 'address' => 'nullable|string|max:255', 'phone' => 'nullable|string|max:30', 'email' => 'nullable|email|max:255', 'verification_information' => 'nullable|string|max:5000', 'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120']);
        unset($data['logo']);
        $agency = $r->attributes->get('agency');
        if (($data['verification_information'] ?? null) !== $agency->verification_information) {
            $data['verification_status'] = 'pending';
        }
        if ($r->hasFile('logo')) {
            $upload = app(MediaUploadService::class)->uploadOne('logo');
            if (($upload['code'] ?? 0) !== 1) {
                $this->fail('Không thể lưu logo.');
            } $data['logo'] = $upload['name'];
        }
        $agency->update($data);

        return back()->with('success', 'Đã lưu hồ sơ đại lý.');
    }

    public function account(Request $r)
    {
        $user = auth('admins')->user();
        $data = $r->validate([
            'account_name' => 'required|string|max:191',
            'account_email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($user->id)],
            'account_phone' => 'nullable|string|max:30',
        ]);
        $user->forceFill([
            'name' => $data['account_name'],
            'email' => $data['account_email'],
            'phone' => $data['account_phone'] ?? null,
        ])->save();

        return back()->with('success', 'Đã lưu thông tin tài khoản.');
    }

    public function createTourForm(Request $r)
    {
        return $this->showTourForm($r, null);
    }

    public function editTourForm(Request $r, int $id)
    {
        return $this->showTourForm($r, $this->tours($r)->findOrFail($id));
    }

    private function showTourForm(Request $r, ?Tour $tour)
    {
        return view('admin.agency.tour-form', [
            'tour' => $tour,
            'agency' => $r->attributes->get('agency'),
            'locations' => Location::active()->orderBy('l_name')->get(),
            'status' => Tour::STATUS,
            'isAgencyForm' => true,
            'formAction' => $tour ? route('agency.tour.update', $tour->id) : route('agency.tour.create'),
        ]);
    }

    public function tour(Request $r, ?int $id = null)
    {
        $tour = $id ? $this->tours($r)->findOrFail($id) : new Tour;
        $data = $r->validate([
            't_title' => 'required|string|max:191',
            't_location_id' => 'nullable|exists:locations,id',
            't_type' => ['nullable', Rule::in(array_keys(Tour::TOUR_TYPES))],
            't_journeys' => 'nullable|string|max:255',
            't_description' => 'nullable|string|max:50000',
            't_content' => 'nullable|string|max:50000',
            't_move_method' => 'nullable|string|max:191',
            't_starting_gate' => 'nullable|string|max:191',
            'cancellation_policy' => 'required|string|max:10000',
            't_price_adults' => 'required|integer|min:0|max:2000000000',
            't_price_children' => 'required|integer|min:0|max:2000000000',
            't_sale' => 'nullable|integer|between:0,100',
            't_duration_days' => 'required|integer|between:1,60',
            't_duration_nights' => 'nullable|integer|between:0,59',
            't_status' => 'required|integer|in:1,2,3,4',
            'images' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:max_width=8000,max_height=8000',
            'album_images' => 'nullable|array|max:12',
            'album_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:max_width=8000,max_height=8000',
            'remove_album_images' => 'nullable|array',
            'remove_album_images.*' => 'integer|min:0',
            'activity_title' => 'nullable|array|max:30',
            'activity_title.*' => 'nullable|string|max:191',
            'activity_icon' => 'nullable|array|max:30',
            'activity_icon.*' => 'nullable|string|max:80',
            'activity_description' => 'nullable|array|max:30',
            'activity_description.*' => 'nullable|string|max:1000',
        ]);
        if (in_array((int) $data['t_status'], [1, 2], true) && (! $tour->exists || (int) $tour->t_status !== (int) $data['t_status'])) {
            throw ValidationException::withMessages(['t_status' => 'Chỉ quản trị viên được xuất bản hoặc tạm ngưng tour.']);
        }
        $nights = isset($data['t_duration_nights']) ? (int) $data['t_duration_nights'] : max(0, (int) $data['t_duration_days'] - 1);
        if ($nights > (int) $data['t_duration_days']) {
            throw ValidationException::withMessages(['t_duration_nights' => 'Số đêm không được lớn hơn số ngày.']);
        }
        $policy = $data['cancellation_policy'];
        $activities = [];
        foreach ($data['activity_title'] ?? [] as $index => $title) {
            $description = trim((string) ($data['activity_description'][$index] ?? ''));
            if (trim((string) $title) !== '' || $description !== '') {
                $activities[] = ['title' => trim((string) $title), 'icon' => trim((string) ($data['activity_icon'][$index] ?? 'fa fa-check-circle')), 'description' => $description];
            }
        }
        $remove = array_map('intval', $data['remove_album_images'] ?? []);
        $album = array_values(array_filter($tour->t_anbum_image ?? [], fn ($image, $index) => ! in_array($index, $remove, true), ARRAY_FILTER_USE_BOTH));
        if (count($album) + count($r->file('album_images', [])) > 12) {
            throw ValidationException::withMessages(['album_images' => 'Album chỉ được có tối đa 12 ảnh.']);
        }
        unset($data['cancellation_policy'], $data['images'], $data['album_images'], $data['remove_album_images'], $data['activity_title'], $data['activity_icon'], $data['activity_description']);
        $data['t_duration_nights'] = $nights;
        $data['t_schedule'] = $data['t_duration_days'].' ngày '.$nights.' đêm';
        $data['t_sale'] = $data['t_sale'] ?? 0;
        $data['t_activities'] = $activities;
        $tour->fill($data);
        $tour->agency_id = $r->attributes->get('agency')->id;
        $tour->cancellation_policy = $policy;
        if (! $id) {
            $tour->t_user_id = auth('admins')->id();
        }
        if ($r->hasFile('images')) {
            $upload = app(MediaUploadService::class)->uploadOne('images');
            if (($upload['code'] ?? 0) !== 1) {
                $this->fail('Không thể lưu hình ảnh.');
            } $tour->t_image = $upload['name'];
        }
        if ($r->hasFile('album_images')) {
            $uploaded = app(MediaUploadService::class)->uploadMany('album_images');
            if (count($uploaded) !== count($r->file('album_images'))) {
                $this->fail('Không thể lưu toàn bộ ảnh album.');
            }
            $album = array_merge($album, $uploaded);
        }
        $tour->t_anbum_image = $album;
        $tour->save();

        return redirect()->route('agency.tour.form.edit', $tour->id)->with('success', 'Đã lưu tour. Tour gửi duyệt cần quản trị viên xuất bản.');
    }

    public function schedule(Request $r, int $id, ?int $scheduleId = null)
    {
        $tour = $this->tours($r)->findOrFail($id);
        $data = $r->validate(['ts_start_date' => 'required|date', 'ts_end_date' => 'required|date|after_or_equal:ts_start_date', 'registration_deadline' => 'required|date|before_or_equal:ts_start_date', 'ts_number_guests' => 'required|integer|between:1,10000', 'adult_price' => 'required|integer|between:0,2000000000', 'child_price' => 'required|integer|between:0,2000000000', 'ts_status' => 'required|in:0,1']);
        DB::transaction(function () use ($tour, $scheduleId, $data) {
            Tour::whereKey($tour->id)->lockForUpdate()->firstOrFail();
            $schedule = $scheduleId ? $tour->schedules()->whereKey($scheduleId)->lockForUpdate()->firstOrFail() : new TourSchedule;
            if ($tour->schedules()->whereDate('ts_start_date', $data['ts_start_date'])->when($scheduleId, fn ($q) => $q->where('id', '!=', $scheduleId))->exists()) {
                $this->fail('Mỗi tour chỉ có một đợt khởi hành trong cùng ngày.');
            }
            $reserved = $scheduleId ? $schedule->bookTours()->where('b_status', '!=', 5)->get()->sum('total_guests') : 0;
            if ($data['ts_number_guests'] < $reserved) {
                $this->fail('Sức chứa không được nhỏ hơn số chỗ đã đặt.');
            }
            if ($reserved && (substr($schedule->ts_start_date, 0, 10) !== $data['ts_start_date'] || substr($schedule->ts_end_date, 0, 10) !== $data['ts_end_date'])) {
                $this->fail('Đợt đã có đăng ký không được đổi ngày đi/về.');
            }
            $schedule->forceFill($data + ['ts_tour_id' => $tour->id])->save();
        });

        return back()->with('success', 'Đã lưu đợt khởi hành.');
    }

    public function status(Request $r, int $id)
    {
        $data = $r->validate(['status' => 'required|in:2,3,4,5', 'note' => 'required_if:status,5|nullable|string|max:2000']);
        $booking = $this->bookings($r)->findOrFail($id);
        try {
            app(BookingService::class)->changeStatus($booking, (int) $data['status'], $data['note'] ?? null, auth('admins')->user(), 'admins');
        } catch (\DomainException $e) {
            $this->fail($e->getMessage());
        }

        return back()->with('success', 'Đã cập nhật trạng thái đăng ký.');
    }

    public function passenger(Request $r, int $id)
    {
        $booking = $this->bookings($r)->findOrFail($id);
        $data = $r->validate(['passenger_id' => 'nullable|integer', 'name' => 'required|string|max:255', 'attendance' => 'required|in:pending,completed,no_show']);
        DB::transaction(function () use ($booking, $data) {
            $booking = BookTour::whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($data['attendance'] !== 'pending' && ((int) $booking->b_status !== 4 || ! $booking->b_end_date || $booking->b_end_date->isFuture())) {
                $this->fail('Chỉ ghi nhận tham gia sau khi đăng ký hoàn tất và chuyến đi kết thúc.');
            }
            $query = BookingPassenger::where('book_tour_id', $booking->id);
            $passenger = ! empty($data['passenger_id']) ? (clone $query)->findOrFail($data['passenger_id']) : new BookingPassenger(['book_tour_id' => $booking->id]);
            if (! $passenger->exists && $query->count() >= $booking->total_guests) {
                $this->fail('Đã đủ số hành khách của đăng ký.');
            }
            $passenger->fill(['name' => $data['name'], 'attendance' => $data['attendance']])->save();
        });

        return back()->with('success', 'Đã lưu hành khách.');
    }

    public function transaction(Request $r, int $id)
    {
        $booking = $this->bookings($r)->findOrFail($id);
        $data = $r->validate(['type' => 'required|in:receipt,refund', 'amount' => 'required|integer|min:1|max:2000000000', 'reference' => 'required|string|max:255|unique:agency_transactions,reference', 'note' => 'required|string|max:2000', 'occurred_at' => 'required|date|before_or_equal:now']);
        DB::transaction(function () use ($booking, $data) {
            $booking = BookTour::whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $entries = AgencyTransaction::where('book_tour_id', $booking->id)->get();
            $paid = $entries->where('type', 'receipt')->sum('amount') + VnpayPayment::where('book_tour_id', $booking->id)->where('status', 'paid')->sum('amount');
            $refunded = $entries->where('type', 'refund')->sum('amount');
            if ($data['type'] === 'refund' && ((int) $booking->b_status !== 5 || $data['amount'] > $paid - $refunded)) {
                $this->fail('Chỉ hoàn tiền cho đăng ký đã hủy, không vượt tiền còn có thể hoàn.');
            }
            if ($data['type'] === 'receipt' && ((int) $booking->b_status === 5 || $paid + $data['amount'] > $booking->total_price)) {
                $this->fail('Không thể thu cho đăng ký đã hủy hoặc vượt giá trị đăng ký.');
            }
            AgencyTransaction::create($data + ['book_tour_id' => $booking->id, 'recorded_by' => auth('admins')->id()]);
        });

        return back()->with('success', 'Đã ghi sổ giao dịch. Đây là ghi nhận, không chuyển tiền qua ngân hàng.');
    }
}
