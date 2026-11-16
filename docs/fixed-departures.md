# Tour có lịch cố định

Tất cả đơn mới bắt buộc thuộc một đợt đã mở, ngày khởi hành sau hôm nay và hạn đăng ký chưa qua. Mỗi người (kể cả trẻ sơ sinh) tính một chỗ. Đơn chưa hủy giữ chỗ; hủy trả chỗ. Giá và ngày về được chụp từ đợt khi tạo đơn, không nhận giá từ trình duyệt.

Admin tạo tour, sau khi lưu được chuyển sang trang sửa để thêm đợt. Đại lý dùng cổng hiện có và chỉ quản lý tour thuộc mình; admin duyệt xuất bản. Tour thiếu đợt không nhận được đơn mới.

## Dữ liệu đang có

Chạy `php artisan tours:reconcile-departures` để xem tour thiếu lịch và đơn cũ chưa có đợt. Dùng `--apply` để gắn đơn khi duy nhất một đợt khớp cả ngày đi/ngày về và còn đủ sức chứa. Không sửa giá, ngày, trạng thái hoặc thanh toán cũ. Các trường hợp không khớp cần admin kiểm tra; không tự tạo sức chứa hay ngày đi. Bổ sung lịch cho tour trước khi mở nhận đơn mới.

Không cần thay đổi schema: dùng `tour_schedules` và `b_tour_schedule_id` đã có. Các bộ đếm cũ trên tour không dùng để kiểm tra sức chứa. Đơn cũ chưa gắn đợt cần được đối soát trước khi mở bán lại đợt tương ứng để tránh bỏ sót khách cũ.

Giữ chỗ hiện không tự hết hạn: nhân viên hủy đơn không tiếp tục để trả chỗ. Kiểm thử SQLite xác minh nghiệp vụ; kiểm tra tải đồng thời trên MySQL/PostgreSQL trước khi phát hành sản xuất. Việc tạo đơn và sửa sức chứa đều khóa tour rồi đợt trong giao dịch.

## Kết quả kiểm tra dữ liệu cục bộ ngày 02/11/2026

Tour đang mở nhưng chưa có đợt hợp lệ: #11, #12, #13, #14, #16.
Đơn chưa thể tự liên kết (không khớp duy nhất hoặc thiếu sức chứa): #13–#22.
Lần kiểm tra chỉ đọc, không sửa dữ liệu. Admin cần nhập lịch và sức chứa thực tế; không dùng ngày hoặc sức chứa giả để mở bán.

Nếu còn đơn chưa hủy, chưa gắn đợt và trùng ngày đi của đợt mới, hệ thống chặn đặt thêm đến khi đối soát xong.

## Phí nền tảng chung

Mặc định 15% cho tất cả đại lý, admin có quyền đầy đủ thay đổi tại `/admin/platform-fee` (0–100%, tối đa hai chữ số thập phân). Tỷ lệ dùng đơn vị basis point: 1500 = 15%. Đơn mới chụp tỷ lệ chung vào `commission_basis_points`; đơn cũ giữ nguyên. Các tỷ lệ cũ trên hồ sơ đại lý không còn quyết định phí cho đơn mới.

Khách không trả thêm phí. Khi đơn hoàn tất, báo cáo tính phí = làm tròn giá trị đơn × tỷ lệ, số tiền đại lý hưởng = giá trị đơn − phí. Đơn hủy không ghi nhận doanh thu hoặc phí. Với thanh toán đại lý trực tiếp thu, đây là số phải đối soát, không phải chuyển tiền hoặc tự động trích tài khoản ngân hàng. Hệ thống hiện chưa thực hiện chi trả tự động.
