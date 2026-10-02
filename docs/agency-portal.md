# Đại lý du lịch trong khu vực quản trị

Đại lý là nhân sự của công ty, dùng chung trang đăng nhập `/admin/login` và menu quản trị. Menu **Đại lý du lịch** có các mục riêng: **Hồ sơ đại lý** (`/admin/agency/profile`), **Thống kê & tài chính** (`/admin/agency`), **Tour & khởi hành** (`/admin/agency/tours`) và **Đăng ký & hành khách** (`/admin/agency/bookings`). Đường dẫn `/agency` cũ chuyển hướng đến trang thống kê.

## Admin cấp quyền

1. Vào **Nhân sự công ty → Tạo người dùng mới** hoặc mở một người dùng đã có.
2. Chọn vai trò **Đại lý du lịch**, nhập **Tên đại lý du lịch** và chọn trạng thái **Hoạt động**.
3. Lưu. Hệ thống tạo hoặc cập nhật hồ sơ đại lý gắn với tài khoản. Vai trò này có quyền `truy-cap-he-thong` và `quan-ly-dai-ly`; không được xem bảng điều khiển, tour hoặc booking toàn hệ thống.
4. Nhân sự đăng nhập tại `/admin/login` và mở mục **Đại lý du lịch** trong menu trái. Tài khoản đại lý có sẵn trước đợt chuyển đổi được gắn vai trò qua migration `2026_09_30_000002_integrate_agencies_with_admin_acl.php`.
5. Đổi vai trò khác hoặc khóa tài khoản trong **Nhân sự công ty** sẽ chặn quyền vào khu vực đại lý. Hồ sơ và dữ liệu tour được giữ lại. Nếu cần cấp lại, chọn vai trò đại lý và kích hoạt tài khoản.

Chạy `php artisan migrate` khi triển khai. `AclSeeder` cũng khai báo vai trò/quyền này cho các môi trường dựng mới. Lệnh `agency:create` vẫn có thể dùng để chuyển tài khoản có sẵn từ dòng lệnh, đồng thời cấp vai trò nhân sự. Quy trình chính dành cho admin là giao diện **Nhân sự công ty**.

## Nghiệp vụ đại lý

Trong **Hồ sơ đại lý**, nhân sự cập nhật tài khoản và thông tin đại lý. **Tour & khởi hành** dùng để tạo tour nháp, gửi duyệt, sửa tour và quản lý đợt khởi hành. **Đăng ký & hành khách** có bộ lọc theo mã/khách hàng, tour, đợt, thời gian và trạng thái; từ đây đại lý xác nhận/hủy đơn, ghi nhận hành khách và thu/hoàn tiền. **Thống kê & tài chính** chỉ hiển thị các số liệu và bộ lọc báo cáo. Nút **Tạo tour mới** mở form cùng mẫu với admin, gồm địa điểm, loại tour, giá, hành trình, số ngày/đêm, nội dung, lịch trình, hoạt động, ảnh bìa và album; đại lý bổ sung chính sách hủy. Mục hướng dẫn viên chung của admin không hiện với đại lý. Mỗi tài khoản chỉ truy cập tour, đợt khởi hành và đăng ký thuộc hồ sơ đại lý gắn với chính mình. Các ID gửi từ trình duyệt đều được kiểm tra lại.

Tour mới cần admin có quyền duyệt tour xuất bản. Giá, chính sách hủy và hoa hồng được lưu tại thời điểm đặt; cập nhật tour không sửa đăng ký cũ. Đăng ký chưa hủy giữ chỗ. Hoàn tiền chỉ được ghi cho đăng ký đã hủy và không vượt số tiền đã thu còn lại. Ghi sổ thủ công không thực hiện chuyển tiền; VNPay thành công được tổng hợp riêng.

Báo cáo có bộ lọc tháng, năm, khoảng ngày, tour và đợt khởi hành. Bộ lọc mã, khách hàng và trạng thái nằm ở trang **Đăng ký & hành khách**. Lượt đăng ký và giá trị đặt tour theo ngày tạo đơn; thu/hoàn theo ngày giao dịch; doanh thu theo ngày ghi nhận sau khi hoàn tất chuyến đi. Phí nền tảng tính trên tỷ lệ hoa hồng đã lưu từng đơn. Khoản đại lý được hưởng là số liệu báo cáo, chưa phải số tiền đã chi trả. Danh sách hành khách hiện do đại lý nhập, chưa liên kết tài khoản người đi để đếm khách hàng duy nhất. Các tour cũ không tự gán chủ đại lý.
