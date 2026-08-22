<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendBookingStatusMail;
use App\Models\BookTour;
use App\Models\Tour;
use App\Models\User;
use App\Services\AdminAuditLogger;
use App\Services\BookingDocumentService;
use App\Services\BookingService;

class BookTourController extends Controller
{
    protected $bookTour;
    protected $bookingService;
    protected $auditLogger;

    public function __construct(BookTour $bookTour, Tour $tour, BookingService $bookingService, AdminAuditLogger $auditLogger)
    {
        view()->share([
            'book_tour_active' => 'active',
            'status'           => $bookTour::STATUS,
            'classStatus'      => $bookTour::CLASS_STATUS,
        ]);

        view()->composer(['admin.book_tour.*'], function ($view) use ($tour) {
            $view->with('tours', $tour::get());
        });

        $this->bookTour = $bookTour;
        $this->bookingService = $bookingService;
        $this->auditLogger = $auditLogger;
    }

    public function index(Request $request)
    {
        $bookTours = $this->filteredBookings($request)
            ->orderByDesc('id')
            ->paginate(NUMBER_PAGINATION_PAGE)
            ->appends($request->query());
        $staffUsers = $this->staffUsers();
        $canManageAllBookings = $this->canManageAllBookings(auth('admins')->user());

        return view('admin.book_tour.index', compact('bookTours', 'staffUsers', 'canManageAllBookings'));
    }

    public function export(Request $request, string $format, BookingDocumentService $documents)
    {
        if (!in_array($format, ['csv', 'pdf'], true)) {
            abort(404);
        }

        $bookTours = $this->filteredBookings($request)
            ->orderByDesc('id')
            ->limit(2000)
            ->get();

        return $format === 'csv'
            ? $documents->exportCsv($bookTours)
            : $documents->exportPdf($bookTours);
    }

    public function downloadConfirmation($id, BookingDocumentService $documents)
    {
        $bookTour = BookTour::with(['tour', 'user'])->findOrFail($id);

        return $documents->confirmation($bookTour);
    }

    public function updateOperation(Request $request, $id)
    {
        $bookTour = BookTour::find($id);
        if (!$bookTour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $admin = auth('admins')->user();
        $canManageAllBookings = $this->canManageAllBookings($admin);
        if (!$canManageAllBookings && !$this->canOperateAssignedBooking($bookTour, $admin)) {
            abort(403);
        }

        $data = $request->validate([
            'b_assigned_staff_id' => 'nullable|integer|exists:users,id',
            'b_internal_note' => 'nullable|string|max:1000',
        ]);

        $oldAssignedStaffId = $bookTour->b_assigned_staff_id;
        $oldInternalNote = $bookTour->b_internal_note;

        if ($canManageAllBookings) {
            $bookTour->b_assigned_staff_id = $data['b_assigned_staff_id'] ?? null;
        }
        $bookTour->b_internal_note = trim((string) ($data['b_internal_note'] ?? '')) ?: null;
        $bookTour->save();

        $this->auditLogger->log('booking.operation_updated', $bookTour, [
            'old_assigned_staff_id' => $oldAssignedStaffId,
            'new_assigned_staff_id' => $bookTour->b_assigned_staff_id,
            'internal_note_changed' => $oldInternalNote !== $bookTour->b_internal_note,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thông tin vận hành booking thành công');
    }

    public function delete($id)
    {
        $bookTour = BookTour::find($id);
        if (!$bookTour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        \DB::beginTransaction();
        try {
            $bookTour->delete();
            $this->auditLogger->log('booking.deleted', $bookTour, [
                'tour_id' => $bookTour->b_tour_id,
                'status' => (int) $bookTour->b_status,
            ]);
            \DB::commit();

            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }

    public function updateStatus(Request $request, $status, $id)
    {
        $bookTour = BookTour::find($id);
        if (!$bookTour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        if (!$this->canOperateAssignedBooking($bookTour, auth('admins')->user())) {
            abort(403);
        }

        $newStatus = (int) $status;
        $data = $request->validate([
            'status_note' => 'nullable|string|max:500',
            'cancel_reason' => $newStatus === BookTour::STATUS_CANCELLED ? 'required|string|max:500' : 'nullable|string|max:500',
        ]);
        $oldStatus = (int) $bookTour->b_status;
        $note = $newStatus === BookTour::STATUS_CANCELLED
            ? ($data['cancel_reason'] ?? null)
            : ($data['status_note'] ?? null);

        try {
            $result = $this->bookingService->changeStatus($bookTour, $newStatus, $note, auth('admins')->user(), 'admins');
            $this->dispatchStatusMail($result);
            $this->auditLogger->log('booking.status_updated', $result['bookTour'], [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'tour_id' => $result['tour']->id,
                'cancel_reason' => $newStatus === BookTour::STATUS_CANCELLED ? $note : null,
            ]);

            return redirect()->route('book.tour.index')->with('success', 'Cập nhật trạng thái thành công');
        } catch (\DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật trạng thái');
        }
    }

    private function dispatchStatusMail(array $result): void
    {
        if (!$result['mail'] || !$result['user']) {
            return;
        }

        SendBookingStatusMail::dispatch(
            $result['user']->id,
            $result['bookTour']->id,
            $result['tour']->id,
            $result['mail']
        );
    }

    private function filteredBookings(Request $request)
    {
        $bookTours = BookTour::with(['tour', 'user', 'schedule', 'assignedStaff']);
        $admin = auth('admins')->user();
        $canManageAllBookings = $this->canManageAllBookings($admin);
        $bookingCode = trim((string) $request->input('booking_code', $request->input('booking_id', '')));

        if ($bookingCode !== '') {
            $bookTours->where(function ($query) use ($bookingCode) {
                $query->where('b_code', 'like', '%' . $bookingCode . '%');

                if (ctype_digit($bookingCode)) {
                    $query->orWhere('id', (int) $bookingCode);
                }
            });
        }

        if ($request->name_tour) {
            $nameTour = $request->name_tour;
            $bookTours->whereIn('b_tour_id', function ($q) use ($nameTour) {
                $q->from('tours')
                    ->select('id')
                    ->where('t_title', 'like', '%' . $nameTour . '%');
            });
        }

        if ($request->b_tour_id) {
            $bookTours->where('b_tour_id', $request->b_tour_id);
        }
        if ($request->b_name) {
            $bookTours->where('b_name', 'like', '%' . $request->b_name . '%');
        }
        if ($request->b_email) {
            $bookTours->where('b_email', 'like', '%' . $request->b_email . '%');
        }
        if ($request->b_phone) {
            $bookTours->where('b_phone', 'like', '%' . $request->b_phone . '%');
        }
        if ($request->b_user_id) {
            $bookTours->where('b_user_id', (int) $request->b_user_id);
        }
        if ($request->customer) {
            $customer = $request->customer;
            $bookTours->where(function ($query) use ($customer) {
                $query->where('b_name', 'like', '%' . $customer . '%')
                    ->orWhere('b_email', 'like', '%' . $customer . '%')
                    ->orWhere('b_phone', 'like', '%' . $customer . '%')
                    ->orWhereHas('user', function ($userQuery) use ($customer) {
                        $userQuery->where('name', 'like', '%' . $customer . '%')
                            ->orWhere('email', 'like', '%' . $customer . '%')
                            ->orWhere('phone', 'like', '%' . $customer . '%');
                    });
            });
        }
        if ($request->b_start_date) {
            $bookTours->whereDate('b_start_date', $request->b_start_date);
        }
        if ($request->b_start_date_from) {
            $bookTours->whereDate('b_start_date', '>=', $request->b_start_date_from);
        }
        if ($request->b_start_date_to) {
            $bookTours->whereDate('b_start_date', '<=', $request->b_start_date_to);
        }
        if ($request->filled('b_assigned_staff_id')) {
            $assignedStaffId = $request->input('b_assigned_staff_id');
            if ($canManageAllBookings) {
                $assignedStaffId === 'unassigned'
                    ? $bookTours->whereNull('b_assigned_staff_id')
                    : $bookTours->where('b_assigned_staff_id', (int) $assignedStaffId);
            }
        }
        if ($request->filled('b_status')) {
            $statusFilter = $request->input('b_status');
            $statusValues = $statusFilter === 'pending'
                ? [1, 2]
                : (is_array($statusFilter) ? $statusFilter : [$statusFilter]);
            $validStatuses = [];

            foreach ($statusValues as $statusValue) {
                $statusValue = (int) $statusValue;
                if (array_key_exists($statusValue, BookTour::STATUS)) {
                    $validStatuses[] = $statusValue;
                }
            }

            if (!empty($validStatuses)) {
                $bookTours->whereIn('b_status', $validStatuses);
            }
        }

        if (!$canManageAllBookings && $admin) {
            $bookTours->where('b_assigned_staff_id', $admin->id);
        }

        return $bookTours;
    }

    private function staffUsers()
    {
        return User::whereHas('userRole', function ($role) {
                $role->whereNotIn('name', ['khach-hang', 'khach_hang', 'customer']);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    private function canManageAllBookings(?User $admin): bool
    {
        return $admin && (
            $admin->can('full-quyen-quan-ly')
            || $admin->hasRoleName('quan-ly-van-hanh')
        );
    }

    private function canOperateAssignedBooking(BookTour $booking, ?User $admin): bool
    {
        if (!$admin || !$admin->can(['full-quyen-quan-ly', 'cap-nhat-trang-thai-dat-tour'])) {
            return false;
        }

        if ($this->canManageAllBookings($admin)) {
            return true;
        }

        return (int) $booking->b_assigned_staff_id === (int) $admin->id;
    }
}
