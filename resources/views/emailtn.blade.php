@php
    $startDate = $book->b_start_date;
    $endDate = $book->b_end_date;

    $adultTotal = (int) $book->b_number_adults * (float) $book->b_price_adults;
    $childrenTotal = (int) $book->b_number_children * (float) $book->b_price_children;
    $child6Total = (int) $book->b_number_child6 * (float) $book->b_price_child6;
    $child2Total = (int) $book->b_number_child2 * (float) $book->b_price_child2;
    $totalGuests = (int) $book->b_number_adults + (int) $book->b_number_children + (int) $book->b_number_child6 + (int) $book->b_number_child2;
    $totalPrice = $adultTotal + $childrenTotal + $child6Total + $child2Total;
@endphp

<div style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#172033;">
    <div style="max-width:760px;margin:0 auto;padding:24px 12px;">
        <div style="background:#ffffff;border:1px solid #e5eaf2;border-radius:16px;overflow:hidden;box-shadow:0 18px 42px rgba(15,23,42,.08);">
            <div style="background:linear-gradient(135deg,#111827,#243449);padding:28px 26px;color:#ffffff;">
                <div style="font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#ffb199;">Miu Travel</div>
                <h1 style="margin:8px 0 8px;font-size:26px;line-height:1.3;color:#ffffff;">Đã tiếp nhận yêu cầu đặt tour</h1>
                <p style="margin:0;color:rgba(255,255,255,.78);font-size:15px;line-height:1.6;">
                    Cảm ơn quý khách đã chọn tour theo ngày khởi hành mong muốn. Đơn của quý khách đang chờ Miu Travel xác nhận.
                </p>
            </div>

            <div style="padding:24px 26px;">
                <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:14px;padding:16px 18px;margin-bottom:20px;">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="vertical-align:middle;">
                                <div style="font-size:13px;color:#9a3412;font-weight:bold;">Mã booking</div>
                                <div style="font-size:28px;color:#ea580c;font-weight:900;line-height:1.2;">#{{ $book->id }}</div>
                            </td>
                            <td style="vertical-align:middle;text-align:right;">
                                <span style="display:inline-block;padding:8px 13px;background:#ea580c;color:#ffffff;border-radius:999px;font-size:12px;font-weight:bold;">
                                    ĐANG CHỜ XÁC NHẬN
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#334155;">
                    Xin chào <strong>{{ $book->b_name ?: $user->name }}</strong>,<br>
                    Miu Travel đã nhận được thông tin đặt tour của quý khách. Nhân viên tư vấn sẽ liên hệ để xác nhận lịch trình và hướng dẫn các bước tiếp theo.
                </p>

                <div style="margin-bottom:18px;">
                    <h2 style="margin:0 0 10px;font-size:16px;color:#111827;">Thông tin tour</h2>
                    <table style="width:100%;border-collapse:collapse;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;font-size:14px;">
                        <tr>
                            <td style="padding:11px 14px;width:180px;color:#64748b;border-bottom:1px solid #e2e8f0;">Tour</td>
                            <td style="padding:11px 14px;color:#0f172a;border-bottom:1px solid #e2e8f0;font-weight:bold;">{{ $tour->t_title }}</td>
                        </tr>
                        <tr>
                            <td style="padding:11px 14px;color:#64748b;border-bottom:1px solid #e2e8f0;">Ngày khởi hành mong muốn</td>
                            <td style="padding:11px 14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                                {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Đang cập nhật' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:11px 14px;color:#64748b;border-bottom:1px solid #e2e8f0;">Ngày về dự kiến</td>
                            <td style="padding:11px 14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                                {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Đang cập nhật' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:11px 14px;color:#64748b;">Điểm đón</td>
                            <td style="padding:11px 14px;color:#0f172a;">{{ $book->b_address ?: 'Đang cập nhật' }}</td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom:18px;">
                    <h2 style="margin:0 0 10px;font-size:16px;color:#111827;">Thông tin khách hàng</h2>
                    <table style="width:100%;border-collapse:collapse;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;font-size:14px;">
                        <tr>
                            <td style="padding:11px 14px;width:180px;color:#64748b;border-bottom:1px solid #e2e8f0;">Họ tên</td>
                            <td style="padding:11px 14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $book->b_name ?: $user->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding:11px 14px;color:#64748b;border-bottom:1px solid #e2e8f0;">Email</td>
                            <td style="padding:11px 14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $book->b_email ?: $user->email }}</td>
                        </tr>
                        <tr>
                            <td style="padding:11px 14px;color:#64748b;border-bottom:1px solid #e2e8f0;">Số điện thoại</td>
                            <td style="padding:11px 14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $book->b_phone ?: $user->phone }}</td>
                        </tr>
                        <tr>
                            <td style="padding:11px 14px;color:#64748b;">Ghi chú</td>
                            <td style="padding:11px 14px;color:#0f172a;">{{ $book->b_note ?: 'Không có' }}</td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom:18px;">
                    <h2 style="margin:0 0 10px;font-size:16px;color:#111827;">Số lượng và chi phí</h2>
                    <table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;font-size:14px;">
                        <thead>
                            <tr style="background:#f1f5f9;color:#475569;">
                                <th style="padding:11px 12px;text-align:left;">Loại khách</th>
                                <th style="padding:11px 12px;text-align:center;">Số lượng</th>
                                <th style="padding:11px 12px;text-align:right;">Đơn giá</th>
                                <th style="padding:11px 12px;text-align:right;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;">Người lớn</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:center;">{{ (int) $book->b_number_adults }}</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:right;">{{ number_format($book->b_price_adults, 0, ',', '.') }}đ</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:right;font-weight:bold;">{{ number_format($adultTotal, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;">Trẻ em</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:center;">{{ (int) $book->b_number_children }}</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:right;">{{ number_format($book->b_price_children, 0, ',', '.') }}đ</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:right;font-weight:bold;">{{ number_format($childrenTotal, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;">Trẻ 2-6 tuổi</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:center;">{{ (int) $book->b_number_child6 }}</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:right;">{{ number_format($book->b_price_child6, 0, ',', '.') }}đ</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:right;font-weight:bold;">{{ number_format($child6Total, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;">Dưới 2 tuổi</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:center;">{{ (int) $book->b_number_child2 }}</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:right;">{{ number_format($book->b_price_child2, 0, ',', '.') }}đ</td>
                                <td style="padding:11px 12px;border-top:1px solid #e2e8f0;text-align:right;font-weight:bold;">{{ number_format($child2Total, 0, ',', '.') }}đ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div style="background:#ecfdf5;border:1px solid #bbf7d0;border-radius:14px;padding:16px 18px;margin-bottom:18px;">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="color:#166534;font-size:14px;font-weight:bold;">Số khách</td>
                            <td style="text-align:right;color:#166534;font-size:18px;font-weight:900;">{{ $totalGuests }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top:8px;color:#9a3412;font-size:14px;font-weight:bold;">Tổng tiền tạm tính</td>
                            <td style="padding-top:8px;text-align:right;color:#ea580c;font-size:24px;font-weight:900;">{{ number_format($totalPrice, 0, ',', '.') }}đ</td>
                        </tr>
                    </table>
                </div>

                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:15px 16px;color:#1e3a8a;font-size:14px;line-height:1.7;">
                    <strong>Lưu ý:</strong> Tổng tiền trên là tạm tính theo số khách đã chọn. Miu Travel sẽ liên hệ xác nhận lại lịch trình trước khi chốt booking. Quý khách vui lòng lưu mã booking để thuận tiện khi cần tra cứu hoặc trao đổi với nhân viên tư vấn.
                </div>
            </div>

            <div style="padding:18px 26px;background:#f8fafc;border-top:1px solid #e2e8f0;color:#64748b;font-size:13px;line-height:1.6;text-align:center;">
                Miu Travel cảm ơn quý khách đã tin tưởng lựa chọn dịch vụ của chúng tôi.
            </div>
        </div>
    </div>
</div>
