# Thanh toán VNPay

Luồng: khách chọn Thanh toán online trên form đặt tour hoặc trong Tour đã đặt (đơn chờ xác nhận/đã xác nhận) → chọn phương thức trên VNPay → IPN hoặc kết quả trả về có chữ ký hợp lệ cập nhật booking và lịch sử trạng thái sang Đã thanh toán. Admin và khách hàng đọc cùng trạng thái booking từ database. Hai luồng dùng chung xử lý chống ghi nhận trùng.

## Thiết lập

1. Chạy `php artisan migrate`.
2. Điền `VNP_TMN_CODE`, `VNP_HASH_SECRET`, `VNP_URL` vào `.env` bằng thông tin VNPay cấp cho website. Mặc định URL là sandbox. Không dùng lại khóa của dự án mẫu.
3. Đặt `APP_URL` bằng địa chỉ HTTPS công khai của website, chạy `php artisan config:clear` (hoặc tạo lại config cache khi triển khai).
4. Đăng ký IPN URL với VNPay: `https://<domain>/api/vnpay/ipn`. Return URL: `https://<domain>/vnpay/return`. Máy chủ VNPay phải truy cập được IPN, localhost không nhận được IPN từ bên ngoài.
5. Thử trên sandbox: thành công, hủy, sai chữ ký/số tiền, IPN lặp, và đóng trình duyệt trước khi quay lại. Xác nhận booking chỉ chuyển sang Đã thanh toán sau kết quả thành công đã xác minh chữ ký, mã giao dịch và số tiền. IPN vẫn cần thiết khi khách đóng trang trước khi quay về website.

Giao dịch được lưu trong `vnpay_payments` cùng mã tham chiếu, số tiền VND, mã VNPay, ngân hàng và trạng thái. Yêu cầu đang hiệu lực được dùng lại trong 15 phút để hạn chế thanh toán nhiều tab. Giao dịch thành công đến sau khi booking bị hủy/đổi tiền/đã thanh toán được lưu `review` và thông báo quản trị để đối soát; không tự khôi phục booking hoặc tự hoàn tiền. Hoàn tiền thực hiện qua VNPay sau đối soát.

Tài liệu chính thức: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html
