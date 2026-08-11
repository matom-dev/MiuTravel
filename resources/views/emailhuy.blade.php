@php
    $totalGuests = $bookTour->total_guests;
    $totalPrice = $bookTour->total_price;
@endphp

<div style="max-width: 720px; margin: 0 auto; background: #ffffff; font-family: Arial, Helvetica, sans-serif; color: #1f2937; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
    <div style="background: linear-gradient(135deg, #991b1b, #dc2626); padding: 28px 24px; text-align: center; color: #ffffff;">
        <h1 style="margin: 0; font-size: 26px; line-height: 1.4;">THÔNG BÁO HỦY BOOKING</h1>
        <p style="margin: 8px 0 0; font-size: 15px;">Booking của quý khách đã được hủy theo yêu cầu hoặc theo quy định của hệ thống.</p>
    </div>

    <div style="padding: 24px;">
        <div style="margin-bottom: 18px; padding: 14px 16px; background: #fef2f2; border-left: 5px solid #dc2626; border-radius: 8px;">
            <strong style="color: #991b1b; font-size: 16px;">Trạng thái:</strong>
            <span style="display: inline-block; margin-left: 8px; padding: 4px 10px; background: #dc2626; color: #ffffff; border-radius: 999px; font-size: 13px; font-weight: bold;">ĐÃ HỦY</span>
        </div>

        <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.7;">
            Chào <strong>{{ $user->name }}</strong>,<br>
            Chúng tôi đã nhận được thông tin hủy booking của quý khách. Dưới đây là chi tiết đơn đặt tour đã được hủy.
        </p>

        <div style="background: #f8fafc; border: 1px solid #dbeafe; border-radius: 10px; padding: 18px; margin-bottom: 18px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td style="padding: 8px 0; width: 180px; color: #475569;"><strong>Mã tour</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $bookTour->b_tour_id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #475569;"><strong>Tour</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $tour->t_title }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #475569;"><strong>Điểm khởi hành</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $bookTour->b_address }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #475569;"><strong>Ngày khởi hành mong muốn</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $bookTour->b_start_date ? \Carbon\Carbon::parse($bookTour->b_start_date)->format('d/m/Y') : '---' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #475569;"><strong>Ngày về dự kiến</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $bookTour->b_end_date ? \Carbon\Carbon::parse($bookTour->b_end_date)->format('d/m/Y') : '---' }}</td>
                </tr>
            </table>
        </div>

        <div style="background: #fff7ed; border: 1px solid #fdba74; border-radius: 10px; padding: 18px; margin-bottom: 18px;">
            <div style="font-size: 14px; line-height: 1.8;">
                <div style="margin-bottom: 8px;">
                    <strong style="color: #b45309;">Mã booking:</strong>
                    <span style="color: #dc2626; font-weight: bold;">{{ $bookTour->id }}</span>
                </div>
                <div><strong>Số khách:</strong> {{ number_format($totalGuests) }}</div>
                <div><strong>Tổng tiền tạm tính:</strong> {{ number_format($totalPrice, 0, ',', '.') }} VND</div>
                <div><strong>Ngày booking:</strong> {{ $bookTour->created_at ? $bookTour->created_at->format('d/m/Y H:i') : '---' }}</div>
                <div><strong>Ngày hủy:</strong> {{ $bookTour->updated_at ? $bookTour->updated_at->format('d/m/Y H:i') : '---' }}</div>
                <div style="color: #dc2626; font-weight: bold; margin-top: 6px;">Nếu có thắc mắc, quý khách vui lòng liên hệ letoantrung73@gmail.com</div>
            </div>
        </div>

        <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 18px; margin-bottom: 18px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td style="padding: 8px 0; color: #475569; width: 180px;"><strong>Họ tên</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #475569;"><strong>Email</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #475569;"><strong>Số điện thoại</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $user->phone }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #475569;"><strong>Địa chỉ</strong></td>
                    <td style="padding: 8px 0; color: #0f172a;">{{ $user->address }}</td>
                </tr>
            </table>
        </div>

        <div style="background: #eff6ff; border: 1px solid #93c5fd; border-radius: 10px; padding: 16px; text-align: center; font-size: 14px;">
            <strong>Thông tin hành khách</strong><br>
            Người lớn: <strong>{{ $bookTour->b_number_adults }}</strong>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Trẻ em: <strong>{{ $bookTour->b_number_children }}</strong>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Trẻ 2-6 tuổi: <strong>{{ $bookTour->b_number_child6 }}</strong>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Dưới 2 tuổi: <strong>{{ $bookTour->b_number_child2 }}</strong>
            <br>
            Tổng số khách: <strong>{{ number_format($totalGuests) }}</strong>
        </div>
    </div>
</div>
