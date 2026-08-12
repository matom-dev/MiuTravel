<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendBookingStatusMail;
use App\Models\BookTour;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Services\AdminAuditLogger;
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
        $bookTours = BookTour::with(['tour', 'user', 'schedule']);

        if ($request->booking_id) {
            $bookTours->where('id', (int) $request->booking_id);
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

        $bookTours = $bookTours->orderByDesc('id')->paginate(NUMBER_PAGINATION_PAGE);
        return view('admin.book_tour.index', compact('bookTours'));
    }

    public function delete($id)
    {
        $bookTour = BookTour::find($id);
        if (!$bookTour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $numberUser = $bookTour->b_number_adults
                    + $bookTour->b_number_children
                    + $bookTour->b_number_child6
                    + $bookTour->b_number_child2;

        \DB::beginTransaction();
        try {
            $tour = Tour::where('id', $bookTour->b_tour_id)->lockForUpdate()->first();
            $schedule = $bookTour->b_tour_schedule_id
                ? TourSchedule::where('id', $bookTour->b_tour_schedule_id)->lockForUpdate()->first()
                : null;

            if ((int) $bookTour->b_status === 1) {
                if ($tour) {
                    $tour->t_follow = max(0, $tour->t_follow - $numberUser);
                    $tour->save();
                }
                if ($schedule) {
                    $schedule->ts_follow = max(0, $schedule->ts_follow - $numberUser);
                    $schedule->save();
                }
            } elseif (in_array((int) $bookTour->b_status, [2, 3], true)) {
                if ($tour) {
                    $tour->t_number_registered = max(0, $tour->t_number_registered - $numberUser);
                    $tour->save();
                }
                if ($schedule) {
                    $schedule->ts_number_registered = max(0, $schedule->ts_number_registered - $numberUser);
                    $schedule->save();
                }
            }

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

        $newStatus     = (int) $status;

        try {
            $result = $this->bookingService->changeStatus($bookTour, $newStatus);
            $this->dispatchStatusMail($result);
            $this->auditLogger->log('booking.status_updated', $result['bookTour'], [
                'old_status' => $bookTour->b_status,
                'new_status' => $newStatus,
                'tour_id' => $result['tour']->id,
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
}
