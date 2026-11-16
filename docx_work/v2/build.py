from PIL import Image, ImageDraw, ImageFont
from zipfile import ZipFile, ZIP_DEFLATED
from lxml import etree as E
from io import BytesIO
from pathlib import Path
import json,re,math
OUT=Path('/Users/macair2020/Documents/Projects/docx_work/v2')
font=ImageFont.truetype('/System/Library/Fonts/Supplemental/Arial.ttf',31)
bold=ImageFont.truetype('/System/Library/Fonts/Supplemental/Arial Bold.ttf',31)
small=ImageFont.truetype('/System/Library/Fonts/Supplemental/Arial.ttf',28)
# Message indices refer to participant lifelines. Branches use UML break/alt/opt semantics.
def m(a,b,s,reply=False):return {'type':'m','a':a,'b':b,'s':s,'reply':reply}
def frame(kind,guard,*items):return {'type':kind,'branches':[(guard,list(items))]}
def alt(*branches):return {'type':'alt','branches':list(branches)}
def br(guard,*items):return frame('break',guard,*items)
def opt(guard,*items):return frame('opt',guard,*items)
def std(actor,control):return [actor,'Giao diện',control,'Dữ liệu nghiệp vụ']
D={};ex={}
def add(n,parts,items,exceptions):D[n]={'participants':parts,'items':items};ex[n]=exceptions
add(1,std('Khách truy cập','TourController'),[
m(0,1,'1. Chọn điều kiện tra cứu'),m(1,2,'1.1. Gửi bộ lọc và trang cần xem'),m(2,2,'2. Kiểm tra định dạng bộ lọc'),br('bộ lọc sai',m(2,1,'2a. Yêu cầu điều chỉnh bộ lọc',True)),m(2,3,'2.1. Tìm tour trạng thái 1/2 theo tiêu chí'),m(3,2,'2.2. Kết quả phân trang',True),alt(('có kết quả',[m(2,1,'3. Trả danh sách; giữ bộ lọc khi đổi trang',True)]),('không có kết quả',[m(2,1,'3a. Trả danh sách rỗng; giữ bộ lọc',True)]))],['2a. Bộ lọc sai: yêu cầu điều chỉnh.','3a. Không có kết quả: trả danh sách rỗng.'])
add(2,std('Khách truy cập','TourController'),[
m(0,1,'1. Chọn tour cần xem'),m(1,2,'1.1. Gửi ID tour'),m(2,3,'2. Tìm tour công khai (trạng thái 1/2)'),m(3,2,'2.1. Tour hoặc không tìm thấy',True),alt(('tour tồn tại và công khai',[m(2,3,'3. Lấy nội dung, đại lý, lịch/giá, bình luận đã duyệt'),m(3,2,'3.1. Dữ liệu chi tiết',True),m(2,1,'3.2. Hiển thị chi tiết tour',True)]),('không tồn tại hoặc không công khai',[m(2,1,'2a. Báo không tìm thấy',True)]))],['2a. Tour không tồn tại hoặc không công khai: báo không tìm thấy.'])
add(3,std('Khách truy cập','ChatbotController')+['Gemini'],[
m(0,1,'1. Gửi câu hỏi'),m(1,2,'1.1. Gửi nội dung câu hỏi'),m(2,2,'1.2. Kiểm tra nội dung; xác định user/cookie'),br('trống hoặc quá 2.000 ký tự',m(2,1,'1a. Từ chối câu hỏi',True)),m(2,3,'2. Lưu tin khách theo phiên'),m(2,3,'2.1. Lấy tour/khách sạn và lịch sử gần nhất'),m(3,2,'2.2. Ngữ cảnh hội thoại',True),alt(('có cấu hình AI',[m(2,4,'3. Gửi câu hỏi kèm ngữ cảnh'),alt(('AI phản hồi',[m(4,2,'3.1. Trả câu trả lời',True)]),('AI lỗi',[m(4,2,'3a. Báo lỗi AI',True),m(2,2,'3a.1. Dùng phản hồi dự phòng')]))]),('thiếu cấu hình',[m(2,2,'3b. Dùng phản hồi dự phòng')])),m(2,3,'4. Lưu phản hồi trợ lý/dự phòng'),m(2,1,'4.1. Trả hội thoại của phiên',True)],['1a. Nội dung trống hoặc quá 2.000 ký tự: từ chối.','3a. Lỗi AI: dùng phản hồi dự phòng, giữ tin đã lưu.','3b. Thiếu cấu hình: dùng phản hồi dự phòng, giữ tin đã lưu.'])
add(4,std('Khách truy cập','ChatbotController'),[
m(0,1,'1. Mở cửa sổ hội thoại'),m(1,2,'1.1. Yêu cầu lịch sử'),m(2,2,'2. Xác định user_id hoặc cookie chat_token'),br('khách chưa có token',m(2,1,'2a. Trả lịch sử rỗng',True)),m(2,3,'3. Lọc đúng phiên; sắp xếp theo thời gian'),m(3,2,'3.1. Tin nhắn của phiên',True),alt(('có tin nhắn',[m(2,1,'3.2. Hiển thị lịch sử',True)]),('chưa có tin',[m(2,1,'3a. Trả lịch sử rỗng',True)]))],['2a. Khách chưa có token: trả lịch sử rỗng.','3a. Chưa có tin nhắn: trả lịch sử rỗng.'])
add(5,std('Khách truy cập','RegisterController'),[
m(0,1,'1. Nhập và gửi thông tin đăng ký'),m(1,2,'1.1. Gửi biểu mẫu đăng ký'),m(2,3,'2. Kiểm tra dữ liệu và email chưa dùng'),m(3,2,'2.1. Kết quả kiểm tra',True),br('dữ liệu sai hoặc email trùng',m(2,1,'2a. Trả lỗi biểu mẫu',True)),m(2,2,'3. Băm mật khẩu; bắt đầu giao dịch'),m(2,3,'3.1. Tạo tài khoản với mật khẩu đã băm'),alt(('lưu thành công',[m(3,2,'3.2. Tài khoản mới; commit',True),m(2,2,'4. Đăng nhập tài khoản vừa tạo'),m(2,1,'4.1. Chuyển về trang chủ',True)]),('lỗi lưu',[m(2,3,'3a. Rollback giao dịch'),m(2,1,'3a.1. Báo lỗi; không tạo tài khoản',True)]))],['2a. Email trùng hoặc dữ liệu sai: trả lỗi biểu mẫu.','3a. Lỗi lưu: hoàn tác giao dịch.'])
add(6,['Khách hàng','Giao diện','LoginController','Guard users','User'],[
m(0,1,'1. Nhập email và mật khẩu'),m(1,2,'1.1. Gửi yêu cầu đăng nhập'),m(2,2,'2. Kiểm tra biểu mẫu và giới hạn thử'),br('biểu mẫu sai hoặc vượt giới hạn',m(2,1,'2a. Báo lỗi; không tạo phiên',True)),m(2,3,'3. Xác thực qua guard users'),m(3,4,'3.1. Đối chiếu tài khoản và mật khẩu'),m(4,3,'3.2. Kết quả xác thực',True),m(3,2,'3.3. Kết quả đăng nhập',True),alt(('thông tin đúng',[m(2,2,'4. Tạo phiên khách hàng'),m(2,1,'4.1. Chuyển đến giao diện được phép',True)]),('sai email hoặc mật khẩu',[m(2,1,'3a. Báo lỗi; không tạo phiên',True)]))],['2a. Biểu mẫu sai hoặc vượt giới hạn thử: báo lỗi, không tạo phiên.','3a. Sai tài khoản/mật khẩu: báo lỗi, không tạo phiên.'])
add(7,['Khách hàng','Giao diện','TourController','BookingService','CSDL','Email'],[
m(0,1,'1. Chọn tour, ngày, số khách, liên hệ và cách trả'),m(1,2,'1.1. Gửi yêu cầu đặt tour'),m(2,2,'2. Kiểm tra phiên, ngày đi, số khách và tour'),br('dữ liệu/điều kiện ban đầu sai',m(2,1,'2a. Từ chối; không tạo booking',True)),m(2,3,'3. Yêu cầu tạo booking'),m(3,4,'3.1. Bắt đầu giao dịch; khóa tour; kiểm tra lịch/chỗ'),br('đại lý/lịch/hạn đăng ký/chỗ không hợp lệ',m(3,4,'3a. Rollback'),m(3,2,'3a.1. Báo không đủ điều kiện',True),m(2,1,'3a.2. Không tạo booking',True)),m(3,4,'4. Tính giá; lưu đơn chờ, mã, snapshot và lịch sử'),m(3,4,'5. Commit booking'),m(3,2,'5.1. Trả booking đã lưu',True),m(2,5,'5.2. Thử gửi email tiếp nhận'),opt('email lỗi',m(5,2,'5a. Báo lỗi thư; giữ booking đã commit',True)),alt(('chọn VNPay',[m(2,1,'5.3. Chuyển sang khởi tạo thanh toán UC09',True)]),('cách thanh toán khác',[m(2,1,'5.4. Hiển thị mã booking',True)]))],['2a. Dữ liệu hoặc điều kiện ban đầu sai: không tạo booking.','3a. Đại lý/lịch/hạn đăng ký/chỗ không hợp lệ: rollback, không tạo booking.','5a. Email lỗi sau commit: không hủy booking.'])
add(8,std('Khách hàng','AccountController'),[
m(0,1,'1. Mở danh sách tour đã đặt'),m(1,2,'1.1. Yêu cầu booking cá nhân'),m(2,3,'2. Lọc theo user đang đăng nhập; kèm tour/lịch'),m(3,2,'2.1. Booking thuộc khách',True),alt(('có booking',[m(2,1,'3. Sắp xếp mới nhất; phân trang và trạng thái',True)]),('chưa có booking',[m(2,1,'3a. Hiển thị danh sách rỗng',True)]))],['3a. Chưa có booking: hiển thị danh sách rỗng.'])
add(9,['Khách hàng','Trình duyệt','VnpayController','VnpayService','CSDL','VNPay'],[
m(0,1,'1. Chọn thanh toán booking của mình'),m(1,2,'1.1. Gửi ID booking'),m(2,3,'2. Kiểm tra cấu hình, chủ đơn, trạng thái, tiền'),br('thiếu cấu hình/sai chủ/trạng thái/tiền',m(3,2,'2a. Từ chối khởi tạo',True),m(2,1,'2a.1. Hiển thị lỗi',True)),m(3,4,'3. Khóa booking; đọc pending còn hạn'),br('giá khác pending còn hiệu lực',m(3,4,'3a. Rollback; không tạo lần mới'),m(3,2,'3a.1. Từ chối khởi tạo',True),m(2,1,'3a.2. Hiển thị lỗi',True)),alt(('có pending còn hạn',[m(3,4,'3.1. Dùng lại pending; giữ hạn hiện tại')]),('không có pending còn hạn',[m(3,4,'3.2. Tạo pending mới; hạn 15 phút')])),m(3,3,'4. Sinh URL có chữ ký'),m(3,2,'4.1. URL thanh toán',True),m(2,1,'4.2. HTTP 302 đến URL VNPay',True),m(1,5,'5. Mở cổng VNPay')],['2a. Booking sai chủ/trạng thái, tiền không dương hoặc thiếu cấu hình: từ chối.','3a. Giá khác lần pending còn hiệu lực: từ chối khởi tạo.'])
add(10,['VNPay','VnpayController','VnpayService','CSDL'],[
m(0,1,'1. Gửi tham số IPN'),m(1,2,'2. Xác thực chữ ký, mã website; tìm tham chiếu'),br('chữ ký/mã website sai hoặc không có đơn',m(2,1,'2a. Trả mã 97 hoặc 01',True),m(1,0,'2a.1. JSON RspCode tương ứng',True)),m(2,3,'3. Khóa booking rồi payment; đối chiếu kết quả'),br('số tiền sai / đã xử lý / mã giao dịch sai',m(2,1,'3a. Trả mã 04 / 02 / 99; không ghi lần hai',True),m(1,0,'3a.1. JSON RspCode tương ứng',True)),alt(('thanh toán thất bại',[m(2,3,'4. Ghi payment failed')]),('thành công; booking đủ điều kiện',[m(2,3,'4.1. Ghi payment paid; booking Đã thanh toán; lịch sử')]),('thành công; booking không đủ điều kiện',[m(2,3,'4a. Ghi payment review; không ép đổi booking')])),m(2,3,'5. Lưu và commit kết quả'),m(2,1,'5.1. Mã xử lý',True),m(1,0,'5.2. Trả JSON RspCode',True)],['2a. Chữ ký/mã website sai: 97; không có đơn: 01.','3a. Số tiền sai: 04; đã xử lý: 02; kết quả/mã giao dịch sai: 99.','4a. Thanh toán thành công nhưng booking không đủ điều kiện: payment review, cần đối soát.'])
add(11,['Khách / trình duyệt','Trang kết quả','VnpayController','VnpayService','CSDL'],[
m(0,2,'1. Trở về từ VNPay cùng tham số'),m(2,3,'2. Gọi cơ chế nhận kết quả chung như IPN'),m(3,4,'2.1. Xác thực, đối chiếu; cập nhật có chống lặp'),m(4,3,'2.2. Trạng thái payment đã lưu',True),m(3,2,'2.3. Kết quả xác thực và trạng thái',True),alt(('kết quả đã xác thực',[m(2,1,'3. Hiển thị thành công / thất bại / cần đối soát'),m(1,0,'3.1. Thông báo kết quả',True)]),('tham số sai hoặc chưa xác định',[m(2,1,'2a. Không xác nhận đã trả; hướng dẫn kiểm tra đơn'),m(1,0,'2a.1. Hiển thị hướng dẫn',True)]))],['2a. Tham số sai hoặc chưa xác định: không khẳng định đã thanh toán; hướng dẫn kiểm tra booking.'])
add(12,['Khách hàng','Giao diện','AccountController','BookingService','CSDL','Email'],[
m(0,1,'1. Chọn đơn của mình; nhập lý do hủy'),m(1,2,'1.1. Gửi yêu cầu hủy'),m(2,4,'2. Kiểm tra chủ đơn, Chờ xác nhận và lý do'),br('sai chủ/trạng thái hoặc thiếu lý do',m(2,1,'2a. Từ chối hủy',True)),m(2,3,'3. Yêu cầu chuyển Đã hủy'),m(3,4,'3.1. Khóa booking; đọc lại và kiểm tra chuyển trạng thái'),br('không còn được chuyển trạng thái',m(3,4,'3a. Rollback; giữ trạng thái'),m(3,2,'3a.1. Từ chối hủy',True),m(2,1,'3a.2. Báo không thể hủy',True)),m(3,4,'3.2. Lưu Đã hủy, lý do và lịch sử; commit'),m(3,2,'4. Trả kết quả đã hủy',True),m(2,1,'4.1. Hiển thị kết quả',True),m(2,5,'4.2. Thử gửi email hủy'),opt('email lỗi',m(5,2,'4a. Báo lỗi thư; không khôi phục đơn',True))],['2a. Sai chủ/trạng thái hoặc thiếu lý do: từ chối.','3a. Sau khi khóa, booking không còn được hủy: rollback.','4a. Email lỗi sau lưu: không khôi phục đơn.'])
add(13,std('Đại lý','PortalController'),[
m(0,1,'1. Chọn tour/lịch, trạng thái và kỳ báo cáo'),m(1,2,'1.1. Gửi bộ lọc thống kê'),m(2,2,'1.2. Kiểm tra ngày/tháng/năm'),br('bộ lọc sai',m(2,1,'1a. Trả lỗi bộ lọc',True)),m(2,3,'2. Lọc booking theo agency_id tại lúc đặt'),m(2,3,'3. Tổng hợp booking/khách, thu/hoàn, VNPay paid'),m(3,2,'3.1. Số liệu của đại lý',True),m(2,2,'4. Tính doanh thu ghi nhận, phí, phần đại lý'),alt(('có dữ liệu',[m(2,1,'4.1. Hiển thị chỉ số',True)]),('không có dữ liệu',[m(2,1,'4a. Hiển thị chỉ số 0',True)]))],['1a. Bộ lọc ngày/tháng/năm sai: trả lỗi.','4a. Không có dữ liệu: hiển thị 0.'])
for n in [14,15]:
 verb='Tạo' if n==14 else 'Cập nhật'
 add(n,std('Đại lý','PortalController'),[
 m(0,1,'1. '+('Nhập tour mới' if n==14 else 'Chọn tour của mình; sửa thông tin')),m(1,2,'1.1. Gửi dữ liệu tour'),m(2,3,'2. Xác minh đại lý; '+('quyền tạo tour' if n==14 else 'quyền sở hữu tour')),m(2,2,'2.1. Kiểm tra tên, giá, thời lượng, chính sách, ảnh, trạng thái'),br('sai sở hữu / dữ liệu / trạng thái tự xuất bản',m(2,1,'2a. Từ chối; không lưu tour',True)),m(2,3,'3. '+verb+' tour gắn đại lý hiện tại; lưu ảnh (≤ 12)'),m(3,2,'3.1. Tour đã lưu',True),m(2,1,'4. Trả kết quả; gửi duyệt cần quản trị xuất bản',True)],['2a. Sai sở hữu, dữ liệu không hợp lệ hoặc tự chuyển nhận đặt/tạm ngưng: từ chối.'])
for n in [16,17]:
 add(n,std('Đại lý','PortalController'),[
 m(0,1,'1. '+('Chọn tour của mình; nhập lịch mới' if n==16 else 'Chọn tour và lịch của mình để sửa')),m(1,2,'1.1. Gửi ngày, giá, sức chứa và nhân sự'),m(2,3,'2. Kiểm tra chủ tour/lịch, ngày, hạn đăng ký, giá, nhân sự'),br('sai chủ hoặc dữ liệu không hợp lệ',m(2,1,'2a. Từ chối; không lưu lịch',True)),m(2,3,'3. Khóa tour/lịch; kiểm tra trùng ngày và số chỗ'),br('trùng ngày / sức chứa thiếu'+(' / đổi ngày lịch có khách' if n==17 else ''),m(2,3,'3a. Rollback giao dịch'),m(2,1,'3a.1. Báo lỗi điều kiện lịch',True)),m(2,3,'4. '+('Tạo' if n==16 else 'Cập nhật')+' lịch và phân công hợp lệ trong giao dịch'),m(3,2,'4.1. Commit; lịch đã lưu',True),m(2,1,'4.2. Hiển thị kết quả',True)],['2a. Sai chủ tour/lịch, sai ngày/giá/nhân sự: từ chối, không lưu.','3a. Trùng ngày hoặc sức chứa nhỏ hơn số khách đã đặt'+('; lịch có khách không được đổi ngày đi/về.' if n==17 else ': rollback.')])
for n,status,condition in [(18,'Đã xác nhận','đang Chờ xác nhận'),(19,'Đã thanh toán','Đã xác nhận và đủ tiền'),(20,'Hoàn tất','Đã thanh toán, đủ tiền, chuyến đi kết thúc'),(21,'Đã hủy','chưa Hoàn tất/Đã hủy; có lý do')]:
 add(n,['Đại lý','Giao diện','PortalController','BookingService','CSDL'],[
 m(0,1,'1. Chọn booking; chuyển '+status),m(1,2,'1.1. Gửi trạng thái đích'+(' và lý do hủy' if n==21 else '')),m(2,4,'2. Kiểm tra booking thuộc đại lý hiện tại'),br('sai quyền hoặc sở hữu',m(2,1,'2a. Từ chối; không đổi trạng thái',True)),m(2,3,'3. Yêu cầu chuyển trạng thái'),m(3,4,'3.1. Khóa booking; đọc lại trạng thái'),m(3,3,'3.2. Kiểm tra '+condition),br('không đủ điều kiện chuyển',m(3,4,'3a. Rollback; giữ trạng thái'),m(3,2,'3a.1. Báo lỗi nghiệp vụ',True),m(2,1,'3a.2. Hiển thị từ chối',True)),m(3,4,'4. Lưu '+status+', lý do, lịch sử và thông báo'),m(3,4,'5. Commit thay đổi'),m(3,2,'5.1. Kết quả chuyển trạng thái',True),m(2,1,'5.2. Hiển thị trạng thái mới',True)],['2a. Sai quyền hoặc sở hữu: từ chối, không đổi trạng thái.','3a. Không đủ điều kiện chuyển: rollback, giữ trạng thái.'])
for n,kind in [(22,'receipt'),(23,'refund')]:
 add(n,std('Đại lý','PortalController'),[
 m(0,1,'1. Nhập khoản '+('thu' if n==22 else 'hoàn')+', tham chiếu, ngày và ghi chú'),m(1,2,'1.1. Gửi yêu cầu ghi sổ'),m(2,3,'2. Kiểm tra chủ đơn, tiền > 0, tham chiếu duy nhất, ngày'),br('sai chủ / tiền / tham chiếu / ngày tương lai',m(2,1,'2a. Từ chối; không ghi sổ',True)),m(2,3,'3. Khóa booking; tổng hợp thu/hoàn và VNPay paid'),m(2,2,'3.1. '+('Kiểm tra đơn chưa hủy; tổng thu không vượt giá trị đơn' if n==22 else 'Kiểm tra đơn đã hủy; hoàn không vượt tiền còn được hoàn')),br('sai trạng thái / vượt hạn mức / tham chiếu trùng',m(2,3,'3a. Rollback; không ghi giao dịch'),m(2,1,'3a.1. Báo lỗi',True)),m(2,3,'4. Ghi '+kind+' và người ghi nhận một lần'),m(3,2,'4.1. Commit; mã giao dịch',True),m(2,1,'4.2. Hiển thị kết quả ghi sổ',True)],['2a. Sai chủ, số tiền không dương, tham chiếu trùng hoặc ngày ở tương lai: không ghi sổ.','3a. Sai trạng thái, vượt hạn mức hoặc tham chiếu trùng: rollback, không ghi sổ.'])
add(24,std('Quản trị','HomeController'),[
m(0,1,'1. Chọn năm/tháng báo cáo'),m(1,2,'1.1. Gửi kỳ báo cáo'),m(2,2,'2. Kiểm tra quyền xem doanh thu'),br('không có quyền',m(2,1,'2a. Từ chối truy cập báo cáo',True)),m(2,3,'3. Lọc booking; tổng hợp theo tháng và trạng thái'),m(3,2,'3.1. Kết quả báo cáo',True),alt(('có dữ liệu',[m(2,1,'4. Trả bảng/biểu đồ doanh thu',True)]),('dữ liệu rỗng',[m(2,1,'4a. Trả tập rỗng hoặc chỉ số 0',True)]))],['2a. Không có quyền: từ chối.','4a. Dữ liệu rỗng: biểu diễn tập rỗng hoặc chỉ số 0.'])
for n,status,title in [(25,1,'Còn nhận đặt'),(26,2,'Tạm ngưng nhận đặt')]:
 add(n,std('Quản trị','TourController'),[
 m(0,1,'1. Chọn tour; chuyển '+title),m(1,2,'1.1. Gửi ID tour và status = '+str(status)),m(2,3,'2. Kiểm tra quyền xuất bản, tour và trạng thái đích'),br('thiếu quyền / tour không tồn tại / mã trạng thái sai',m(2,1,'2a. Từ chối; không cập nhật tour',True)),m(2,3,'3. Lưu t_status = '+str(status)+'; ghi nhật ký xuất bản'),m(3,2,'3.1. Trạng thái đã lưu',True),m(2,1,'4. Trả '+title+'; '+('cho phép đặt' if status==1 else 'vẫn cho xem; chặn đặt mới'),True)],['2a. Không có quyền, tour không tồn tại hoặc mã trạng thái sai: từ chối.'])
# Renderer: original diagrams are rebuilt as deterministic UML figures, with the number in each message label.
W=2200; margin=200; ink='#243c59'; line='#6685a7'; faint='#c1ccd8'
def wrap(s,width,f=font):
 out=[]
 for para in s.split('\n'):
  words=para.split();t=''
  for word in words:
   cand=(t+' '+word).strip()
   if f.getlength(cand)>width and t:out.append(t);t=word
   else:t=cand
  out.append(t)
 return out

def render(n,data):
 parts=data['participants']; xs=[margin+i*(W-2*margin)/(len(parts)-1) for i in range(len(parts))]; gap=xs[1]-xs[0]
 # measure then draw in one layout, with wrapped labels and a clear row for every arrow.
 records=[]; frames=[]
 def layout(items,y,depth=0):
  for obj in items:
   if obj['type']=='m':
    a,b=obj['a'],obj['b'];xa,xb=xs[a],xs[b]
    if a==b:
     width=min(gap-35,650);center=min(max(xa+gap*.15, width/2+40),W-width/2-40)
    else:width=abs(xa-xb)-34;center=(xa+xb)/2
    ls=wrap(obj['s'],width);h=len(ls)*38+40
    records.append((obj,y,h,ls,center,width));y+=h
   else:
    top=y;y+=58;divs=[]
    for j,(guard,children) in enumerate(obj['branches']):
     if j:divs.append(y);y+=56
     gy=y-45
     frames.append(('label',obj['type'] if j==0 else '',guard,gy,depth))
     y=layout(children,y,depth+1)+12
    frames.append(('box',obj['type'],top,y,divs,depth));y+=22
  return y
 height=int(layout(data['items'],160)+40)
 im=Image.new('RGB',(W,height),'white');d=ImageDraw.Draw(im)
 for x,p in zip(xs,parts):
  bw=min(gap-32,360);ls=wrap(p,bw-24,bold);bh=100
  d.rounded_rectangle((x-bw/2,12,x+bw/2,12+bh),radius=7,fill='#f1f5f9',outline=line,width=2)
  for j,s in enumerate(ls):d.text((x,62+(j-(len(ls)-1)/2)*37),s,font=bold,anchor='mm',fill=ink)
  for yy in range(113,height-20,21):d.line((x,yy,x,yy+11),fill=faint,width=2)
 for rec in frames:
  if rec[0]=='box':
   _,kind,top,bottom,divs,depth=rec;left=22+depth*12;right=W-22-depth*12
   d.rectangle((left,top,right,bottom),outline=line,width=2)
   for yy in divs:
    for xx in range(left,right,20):d.line((xx,yy,min(xx+10,right),yy),fill=line,width=2)
 for rec in frames:
  if rec[0]=='label':
   _,kind,guard,gy,depth=rec;left=22+depth*12
   if kind:
    tw=small.getlength(kind)+28;d.rectangle((left,gy-13,left+tw,gy+27),fill='#edf2f7',outline=line,width=2);d.text((left+12,gy+7),kind,font=small,anchor='lm',fill=ink);gx=left+tw+16
   else:gx=left+14
   label='['+guard+']'; box=d.textbbox((gx,gy+7),label,font=small,anchor='lm'); d.rectangle((box[0]-2,box[1]-2,box[2]+2,box[3]+2),fill='white'); d.text((gx,gy+7),label,font=small,anchor='lm',fill=ink)
 for obj,y,h,ls,center,width in records:
  for j,s in enumerate(ls):
   # White backing protects text where a lifeline passes under its label.
   box=d.textbbox((center,y+j*38),s,font=font,anchor='mt');d.rectangle((box[0]-3,box[1]-2,box[2]+3,box[3]+2),fill='white');d.text((center,y+j*38),s,font=font,anchor='mt',fill=ink)
  ya=y+len(ls)*38+12;xa,xb=xs[obj['a']],xs[obj['b']]
  if xa==xb:
   turn=xa+min(65,gap/5);d.line((xa,ya-12,turn,ya-12,turn,ya+7,xa,ya+7),fill=line,width=2);d.polygon([(xa,ya+7),(xa+12,ya+1),(xa+12,ya+13)],fill=line)
  else:
   if obj['reply']:
    dist=abs(xa-xb);sgn=1 if xb>xa else -1
    for xx in range(0,int(dist),20):d.line((xa+sgn*xx,ya,xa+sgn*min(xx+11,dist),ya),fill=line,width=2)
   else:d.line((xa,ya,xb,ya),fill=line,width=2)
   sgn=1 if xb>xa else -1;d.polygon([(xb,ya),(xb-sgn*13,ya-7),(xb-sgn*13,ya+7)],fill=line)
 im.save(OUT/f'UC{n:02}.png');return im
ims={n:render(n,data) for n,data in D.items()}
# Use the first delivered DOCX to keep user stories and all untouched source content.
src='/Users/macair2020/Documents/Projects/PhanTichThietKeHeThong_DaCapNhat.docx'
z=ZipFile(src);ns={'w':'http://schemas.openxmlformats.org/wordprocessingml/2006/main','a':'http://schemas.openxmlformats.org/drawingml/2006/main','r':'http://schemas.openxmlformats.org/officeDocument/2006/relationships','wp':'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing'};w='{'+ns['w']+'}'
root=E.fromstring(z.read('word/document.xml'));body=root.find('w:body',ns);rels={x.get('Id'):x.get('Target') for x in E.fromstring(z.read('word/_rels/document.xml.rels'))};replacements={};current=0
for b in list(body):
 text=''.join(b.xpath('.//w:t/text()',namespaces=ns))
 match=re.match(r'UC(\d+) ',text)
 if match:current=int(match[1])
 if text.startswith('Nhánh ') and re.match(r'Nhánh \d+[a-z]',text):body.remove(b);continue
 if text.startswith('Quy ước đánh số Sequence diagram:'):
  for child in list(b):b.remove(child)
  r=E.SubElement(b,w+'r');E.SubElement(r,w+'t').text='Quy ước Sequence diagram: số n đặt trước nội dung thông điệp tương ứng bước n của luồng chính; n.1, n.2 là thông điệp chi tiết; na, nb là nhánh thay thế hoặc ngoại lệ tại bước n. Khung break kết thúc luồng khi điều kiện lỗi đúng; alt chọn một nhánh; opt thực hiện khi điều kiện đúng.'
 if text.startswith('User story:'):
  for t in b.xpath('.//w:t',namespaces=ns):t.set('{http://www.w3.org/XML/1998/namespace}space','preserve')
 if b.tag==w+'tbl' and current in ex:
  for row in b.findall(w+'tr'):
   cells=row.findall(w+'tc')
   if len(cells)>1 and 'Nhánh thay thế' in ''.join(cells[0].xpath('.//w:t/text()',namespaces=ns)):
    cell=cells[1];props=cell.find(w+'tcPr')
    oldp=cell.find(w+'p');ppr=oldp.find(w+'pPr') if oldp is not None else None
    from copy import deepcopy
    for ch in list(cell):
     if ch is not props:cell.remove(ch)
    for label in ex[current]:
     p=E.SubElement(cell,w+'p')
     if ppr is not None:p.append(deepcopy(ppr))
     r=E.SubElement(p,w+'r');E.SubElement(r,w+'t').text=label
bs=list(body);count=0
for i,b in enumerate(bs):
 text=''.join(b.xpath('.//w:t/text()',namespaces=ns))
 if 'Sequence diagram của' not in text:continue
 match=re.search(r'UC(\d+)\.B',text);n=int(match[1]);pic=bs[i-1];rid=pic.xpath('.//a:blip/@r:embed',namespaces=ns)[0];path='word/'+rels[rid]
 buf=BytesIO();ims[n].save(buf,format='PNG');replacements[path]=buf.getvalue();count+=1
 ext=pic.find('.//wp:extent',ns);cx=int(ext.get('cx'));cy=round(cx*ims[n].height/ims[n].width)
 # Cap the diagram to a printable page height; proportional scaling avoids distortion.
 cap=round(7.5*914400)
 if cy>cap:cx=round(cx*cap/cy);cy=cap
 ext.set('cx',str(cx));ext.set('cy',str(cy))
 for e in pic.findall('.//a:xfrm/a:ext',ns):e.set('cx',str(cx));e.set('cy',str(cy))
 # Center diagrams; keep each caption attached to its figure.
 ppr=pic.find(w+'pPr')
 if ppr is None:ppr=E.Element(w+'pPr');pic.insert(0,ppr)
 for old in ppr.findall(w+'jc'):ppr.remove(old)
 jc=E.SubElement(ppr,w+'jc');jc.set(w+'val','center')
 if ppr.find(w+'keepNext') is None:E.SubElement(ppr,w+'keepNext')
 # Meaningful figure description for screen readers.
 for e in pic.findall('.//wp:docPr',ns):e.set('descr',f'Sequence diagram UC{n:02}; số bước nằm trực tiếp trong từng nhãn thông điệp, có luồng chính và nhánh ngoại lệ.')
# Correct source explanatory sentence so scope of customer booking filtering is exact.
for b in body:
 t=''.join(b.xpath('.//w:t/text()',namespaces=ns))
 if t.startswith('AccountController lọc BookTour theo bộ lọc và phạm vi quyền;'):
  texts=b.xpath('.//w:t',namespaces=ns)
  if texts:
   texts[0].text='AccountController chỉ lấy BookTour thuộc tài khoản khách đang đăng nhập; khi chưa có booking, giao diện hiển thị danh sách rỗng.'
   for tx in texts[1:]:tx.text=''
replacements['word/document.xml']=E.tostring(root,xml_declaration=True,encoding='UTF-8',standalone=True)
out='/Users/macair2020/Documents/Projects/PhanTichThietKeHeThong_DanhSoDungMau.docx'
with ZipFile(out,'w',ZIP_DEFLATED) as dest:
 for info in z.infolist():dest.writestr(info,replacements.get(info.filename,z.read(info.filename)))
(OUT/'diagrams.json').write_text(json.dumps(D,ensure_ascii=False,indent=2))
print(out);print('Updated diagrams:',count);print('dimensions:',[(n,im.size) for n,im in ims.items()])
