<div class="card shadow-sm">
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">{{ $listTitle }}</h3>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover booking-overview-table m-0">
            <thead>
                <tr>
                    <th width="6%" class="text-center">STT</th>
                    <th width="10%">Mã đơn</th>
                    <th width="28%">Tour</th>
                    <th width="22%">Khách hàng</th>
                    <th width="14%">Ngày khởi hành</th>
                    <th width="10%" class="text-center">Trạng thái</th>
                    <th width="6%" class="text-center">Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @if (!$bookings->isEmpty())
                    @php $i = $bookings->firstItem(); @endphp
                    @foreach($bookings as $booking)
                        @php
                            $departureDate = $booking->b_start_date;
                            $badgeClass = str_replace('btn-', 'badge-', $classStatus[$booking->b_status] ?? 'badge-secondary');
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $i }}</td>
                            <td><span class="badge badge-light border">#{{ $booking->id }}</span></td>
                            <td>
                                <div class="booking-tour-name">{{ optional($booking->tour)->t_title ?? '---' }}</div>
                                <small class="text-muted">{{ optional($booking->tour)->t_journeys ?? '' }}</small>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $booking->b_name }}</div>
                                <small class="text-muted">{{ $booking->b_phone }}{{ $booking->b_email ? ' - ' . $booking->b_email : '' }}</small>
                            </td>
                            <td>
                                {{ $departureDate ? date('d/m/Y', strtotime($departureDate)) : '---' }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $badgeClass }}">{{ $status[$booking->b_status] ?? 'Không rõ' }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('book.tour.index', ['booking_id' => $booking->id]) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết đơn">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @php $i++ @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-shopping-cart fa-3x mb-3 opacity-50"></i><br>
                            Chưa có đơn đặt tour nào phù hợp.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
        <div class="card-footer bg-white">
            <div class="pagination-wrapper">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
