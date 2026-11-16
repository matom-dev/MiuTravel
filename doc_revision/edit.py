from pathlib import Path
from zipfile import ZipFile,ZIP_DEFLATED
from lxml import etree as E
from PIL import Image,ImageDraw,ImageFont
from io import BytesIO
import re,json,textwrap,math
ROOT=Path('/Users/macair2020/Documents/Projects/doc_revision')
# Messages use the specification step as their prefix. A letter identifies an alternative at that step.
D=[]
def add(story,controller,main,branches,external=None):D.append(dict(story=story,controller=controller,main=main,branches=branches,external=external))
# a = actor, u = UI, c = controller/service, d = data, x = external service
add('Là khách truy cập, tôi muốn tìm tour phù hợp để lựa chọn chuyến đi.','TourController',[
('1','a','u','Chọn điều kiện tra cứu'),('1.1','u','c','Gửi bộ lọc và trang cần xem'),('2','c','d','Kiểm tra bộ lọc; tìm tour trạng thái 1/2 theo tiêu chí'),('3','c','u','Trả danh sách phân trang; giữ bộ lọc khi đổi trang')],[('2a','Bộ lọc sai','c','u','Yêu cầu điều chỉnh bộ lọc'),('3a','Không có kết quả','c','u','Hiển thị danh sách rỗng')])
add('Là khách truy cập, tôi muốn xem thông tin tour để quyết định có đặt tour hay không.','TourController',[
('1','a','u','Chọn tour cần xem'),('1.1','u','c','Gửi ID tour'),('2','c','d','Tìm tour trong phạm vi hiển thị công khai'),('3','c','u','Hiển thị nội dung, đại lý, lịch/giá và bình luận đã duyệt')],[('2a','Tour không tồn tại hoặc không công khai','c','u','Báo không tìm thấy')])
add('Là khách truy cập, tôi muốn hỏi chatbot để được tư vấn chuyến đi.','ChatbotController',[
('1','a','u','Gửi câu hỏi'),('1.1','u','c','Xác định tài khoản hoặc cookie phiên khách'),('2','c','d','Lưu tin khách; tìm tour/khách sạn và lịch sử gần nhất'),('3','c','x','Gửi câu hỏi và ngữ cảnh tới Gemini khi có cấu hình'),('3.1','x','c','Trả phản hồi AI'),('4','c','d','Lưu phản hồi trợ lý theo phiên'),('4.1','c','u','Trả hội thoại')],[('1a','Câu hỏi trống hoặc quá 2.000 ký tự','c','u','Từ chối câu hỏi; không xử lý tiếp'),('3a','Thiếu cấu hình hoặc lỗi Gemini','c','d','Giữ tin khách; lưu phản hồi dự phòng'),('3a.1','Tiếp tục nhánh 3a','c','u','Trả thông báo dự phòng')],'Gemini')
add('Là khách truy cập, tôi muốn xem lại hội thoại để tiếp tục trao đổi với chatbot.','ChatbotController',[
('1','a','u','Mở cửa sổ hội thoại'),('1.1','u','c','Yêu cầu lịch sử chat'),('2','c','c','Xác định user_id hoặc cookie chat_token hiện tại'),('3','c','d','Lấy tin đúng phiên, sắp xếp theo thời gian'),('3.1','c','u','Trả lịch sử hội thoại')],[('2a','Chưa có token','c','u','Trả lịch sử rỗng'),('3a','Chưa có tin nhắn','c','u','Trả lịch sử rỗng')])
add('Là khách truy cập, tôi muốn tạo tài khoản để đặt tour và quản lý đơn của mình.','RegisterController',[
('1','a','u','Nhập và gửi thông tin đăng ký'),('1.1','u','c','Gửi biểu mẫu đăng ký'),('2','c','d','Kiểm tra dữ liệu và email chưa được sử dụng'),('3','c','d','Băm mật khẩu; tạo User trong giao dịch'),('4','c','u','Commit; đăng nhập tài khoản mới; chuyển về trang chủ')],[('2a','Email trùng hoặc dữ liệu sai','c','u','Trả lỗi biểu mẫu; không tạo tài khoản'),('3a','Lỗi lưu tài khoản','c','d','Rollback giao dịch'),('3a.1','Tiếp tục nhánh 3a','c','u','Báo lỗi đăng ký')])
add('Là khách hàng, tôi muốn đăng nhập để sử dụng các chức năng của tài khoản.','LoginController',[
('1','a','u','Nhập email, mật khẩu; gửi đăng nhập'),('1.1','u','c','Gửi thông tin đăng nhập'),('2','c','d','Kiểm tra biểu mẫu, giới hạn thử và tài khoản'),('3','c','d','Xác thực mật khẩu qua guard users'),('4','c','u','Tạo phiên; chuyển tới giao diện được phép')],[('2a','Dữ liệu sai hoặc vượt giới hạn thử','c','u','Báo lỗi; không tạo phiên'),('3a','Sai tài khoản hoặc mật khẩu','c','u','Báo lỗi; không tạo phiên')])
add('Là khách hàng, tôi muốn đặt tour cho nhóm của mình để có mã đơn theo dõi chuyến đi.','TourController / BookingService',[
('1','a','u','Chọn tour, lịch, nhóm tuổi, liên hệ và cách thanh toán'),('1.1','u','c','Gửi yêu cầu đặt tour'),('2','c','c','Kiểm tra đăng nhập, ngày đi, số khách và tour nhận đặt'),('3','c','d','Khóa tour; kiểm tra đại lý, lịch mở, hạn và chỗ trống'),('4','c','d','Tính giá; lưu booking chờ, mã đơn, snapshot và lịch sử'),('5','c','d','Commit dữ liệu booking'),('5.1','c','x','Thử gửi email tiếp nhận sau commit'),('5.2','c','u','Trả mã đơn; nếu chọn VNPay, chuyển UC09')],[('2a','Dữ liệu sai hoặc tour không nhận đặt','c','u','Từ chối; không tạo booking'),('3a','Lịch sai, hết hạn/hết chỗ hoặc đại lý không hoạt động','c','d','Rollback; không tạo booking'),('3a.1','Tiếp tục nhánh 3a','c','u','Thông báo không thể đặt tour'),('5a','Email lỗi sau commit','c','u','Giữ booking; trả kết quả đặt tour')],'Email')
add('Là khách hàng, tôi muốn xem các tour đã đặt để theo dõi trạng thái đơn của mình.','AccountController',[
('1','a','u','Mở danh sách tour đã đặt'),('1.1','u','c','Yêu cầu booking cá nhân'),('2','c','d','Lọc theo tài khoản đăng nhập; kèm tour và lịch'),('3','c','u','Sắp xếp mới nhất, phân trang và hiển thị trạng thái')],[('3a','Chưa có booking','c','u','Hiển thị danh sách rỗng')])
add('Là khách hàng, tôi muốn thanh toán booking qua VNPay để hoàn thành việc trả tiền.','VnpayController / VnpayService',[
('1','a','u','Chọn thanh toán booking của mình'),('1.1','u','c','Gửi ID booking'),('2','c','d','Kiểm tra cấu hình, chủ đơn, trạng thái và tổng tiền > 0'),('3','c','d','Khóa booking; dùng pending còn hạn hoặc tạo lần mới'),('4','c','u','Sinh URL ký số; trả HTTP chuyển hướng'),('5','u','x','Trình duyệt mở URL cổng thanh toán')],[('2a','Thiếu cấu hình, sai chủ hoặc trạng thái không hợp lệ','c','u','Từ chối khởi tạo thanh toán'),('3a','Giá khác lần pending đang hiệu lực','c','u','Từ chối khởi tạo; không đánh dấu đã thanh toán')],'VNPay')
add('Là khách hàng, tôi muốn kết quả thanh toán được ghi nhận chính xác để đơn không bị tính tiền hai lần.','VnpayController / VnpayService',[
('1','a','c','Gửi bộ tham số IPN'),('2','c','d','Xác thực chữ ký, mã website; tìm mã tham chiếu'),('3','c','d','Khóa booking rồi payment; đối chiếu tiền, kết quả, lặp'),('4','c','d','Thất bại: failed; thành công: cập nhật đơn hoặc review'),('5','c','d','Lưu kết quả một lần'),('5.1','c','a','Trả JSON mã phản hồi VNPay')],[('2a','Chữ ký sai','c','a','JSON mã 97'),('2b','Không tìm thấy đơn','c','a','JSON mã 01'),('3a','Số tiền không khớp','c','a','JSON mã 04'),('3b','Giao dịch đã xử lý','c','a','JSON mã 02; không ghi nhận tiền lần hai'),('3c','Kết quả hoặc mã giao dịch sai','c','a','JSON mã 99')])
add('Là khách hàng, tôi muốn xem kết quả thanh toán để biết booking đã được trả tiền hay cần kiểm tra.','VnpayController / VnpayService',[
('1','a','u','Trình duyệt trở về từ VNPay cùng tham số'),('1.1','u','c','Gửi tham số return'),('2','c','d','Xác thực và đối chiếu bằng cơ chế nhận kết quả chung'),('3','c','d','Lấy trạng thái payment đã lưu; chống xử lý lặp'),('3.1','c','u','Hiển thị thành công, thất bại hoặc cần đối soát')],[('2a','Tham số sai','c','u','Không xác nhận đã trả tiền; hướng dẫn kiểm tra booking'),('3a','Chưa xác định được kết quả','c','u','Thông báo cần kiểm tra booking')])
add('Là khách hàng, tôi muốn hủy đơn còn chờ xác nhận khi không thể tham gia chuyến đi.','AccountController / BookingService',[
('1','a','u','Chọn booking; nhập lý do hủy'),('1.1','u','c','Gửi yêu cầu hủy'),('2','c','d','Kiểm tra chủ đơn, lý do và trạng thái Chờ xác nhận'),('3','c','d','Khóa, kiểm tra lại; lưu Đã hủy, lý do và lịch sử'),('4','c','u','Trả kết quả hủy đã lưu'),('4.1','c','x','Thử gửi email hủy sau khi lưu')],[('2a','Sai chủ đơn, thiếu lý do hoặc đã qua Chờ xác nhận','c','u','Từ chối hủy'),('3a','Trạng thái thay đổi khi kiểm tra lại','c','u','Từ chối; không chuyển trạng thái'),('4a','Email lỗi','c','u','Giữ trạng thái Đã hủy')],'Email')
add('Là đại lý, tôi muốn xem số liệu kinh doanh của mình để theo dõi hiệu quả hoạt động.','PortalController',[
('1','a','u','Chọn tour/lịch, trạng thái và khoảng thời gian'),('1.1','u','c','Gửi bộ lọc thống kê'),('2','c','d','Lọc booking theo agency_id tại thời điểm đặt'),('3','c','d','Tổng hợp booking/khách, thu, hoàn và VNPay đã trả'),('4','c','u','Tính doanh thu ghi nhận, phí và phần đại lý; hiển thị')],[('1a','Bộ lọc ngày/tháng/năm sai','c','u','Trả lỗi bộ lọc'),('4a','Không có dữ liệu','c','u','Hiển thị các chỉ số bằng 0')])
for update in [False,True]:
 add('Là đại lý, tôi muốn '+('sửa thông tin tour để cung cấp nội dung chính xác cho khách.' if update else 'tạo tour mới để giới thiệu chuyến đi của mình.'),'PortalController',[
 ('1','a','u','Chọn tour thuộc mình và sửa thông tin' if update else 'Nhập thông tin tour mới'),('1.1','u','c','Gửi dữ liệu tour và ảnh'),('2','c','c','Kiểm tra tên, giá, thời lượng, chính sách, ảnh, trạng thái'),('3','c','d','Gắn đại lý hiện tại; lưu '+('cập nhật tour' if update else 'tour mới')+' và ảnh'),('4','c','u','Trả kết quả; tour gửi duyệt cần quản trị xuất bản')],[('2a','Tour ngoài đại lý hoặc dữ liệu/ảnh sai','c','u','Từ chối; album tối đa 12 ảnh'),('2b','Đại lý chọn nhận đặt hoặc tạm ngưng','c','u','Từ chối; đại lý không tự xuất bản')])
for update in [False,True]:
 add('Là đại lý, tôi muốn '+('sửa lịch khởi hành để cập nhật kế hoạch chuyến đi.' if update else 'tạo lịch khởi hành để khách chọn đợt đi phù hợp.'),'PortalController',[
 ('1','a','u','Chọn tour của mình; '+('mở lịch cần sửa' if update else 'nhập lịch mới')),('1.1','u','c','Gửi ngày, giá, sức chứa và nhân sự'),('2','c','c','Kiểm tra ngày đi/về, hạn, sức chứa, giá và nhân sự'),('3','c','d','Khóa tour/lịch; kiểm tra trùng ngày và số khách đã đặt'),('4','c','d',('Cập nhật' if update else 'Tạo')+' lịch và phân công trong cùng giao dịch'),('4.1','c','u','Commit; trả kết quả')],[('2a','Sai chủ tour hoặc sai ngày/giá/nhân sự','c','u','Từ chối; hoàn tác nếu đã mở giao dịch'),('3a','Trùng ngày hoặc sức chứa nhỏ hơn số khách đã đặt','c','d','Rollback; không lưu lịch'),('3a.1','Tiếp tục nhánh 3a','c','u','Báo lịch không hợp lệ')]+([('3b','Lịch có khách và yêu cầu đổi ngày đi/về','c','u','Từ chối thay đổi ngày')] if update else []))
for state,need,purpose in [
 ('Đã xác nhận','Đang Chờ xác nhận','xác nhận booking để khách biết đơn đã được tiếp nhận'),
 ('Đã thanh toán','Đã xác nhận và đã ghi nhận đủ tiền','đánh dấu đơn đã thanh toán để theo dõi tình trạng trả tiền'),
 ('Hoàn tất','Đã thanh toán; chuyến đi kết thúc và đủ tiền','hoàn tất booking để ghi nhận chuyến đi đã kết thúc'),
 ('Đã hủy','Chưa Hoàn tất/Đã hủy; có lý do hủy','hủy booking kèm lý do để quản lý đơn không tiếp tục thực hiện')]:
 add('Là đại lý, tôi muốn '+purpose+'.','PortalController / BookingService',[
 ('1','a','u','Chọn booking; chuyển sang '+state),('1.1','u','c','Gửi ID booking, trạng thái và lý do nếu có'),('2','c','d','Kiểm tra phạm vi đại lý sở hữu booking'),('3','c','d','Khóa, đọc lại trạng thái; kiểm tra điều kiện chuyển'),('3.1','c','c',need),('4','c','d','Lưu trạng thái, lý do, lịch sử và thông báo cập nhật'),('5','c','u','Commit; hiển thị trạng thái '+state)],[('2a','Sai quyền hoặc booking ngoài đại lý','c','u','Từ chối; không đổi trạng thái'),('3a','Sai trạng thái hoặc không đủ điều kiện nghiệp vụ','c','d','Rollback; giữ trạng thái cũ'),('3a.1','Tiếp tục nhánh 3a','c','u','Thông báo không thể chuyển trạng thái')])
for refund in [False,True]:
 add('Là đại lý, tôi muốn ghi nhận '+('khoản hoàn tiền để theo dõi số tiền đã trả lại khách.' if refund else 'khoản thu để theo dõi số tiền khách đã trả.'),'PortalController',[
 ('1','a','u','Chọn đơn; nhập tiền, tham chiếu, ngày và ghi chú'),('1.1','u','c','Gửi thông tin khoản '+('hoàn' if refund else 'thu')),('2','c','d','Kiểm tra chủ đơn, tiền > 0, tham chiếu và ngày'),('3','c','d','Khóa đơn; tổng hợp thu/hoàn và VNPay để kiểm tra hạn mức'),('3.1','c','c','Đơn '+('đã hủy; hoàn ≤ tiền đã thu trừ đã hoàn' if refund else 'chưa hủy; tổng thu không vượt giá trị booking')),('4','c','d','Ghi '+('refund' if refund else 'receipt')+' và người ghi nhận, một lần'),('4.1','c','u','Trả kết quả ghi sổ')],[('2a','Sai chủ, tiền không dương, trùng tham chiếu hoặc ngày tương lai','c','u','Từ chối; không ghi sổ'),('3a','Sai trạng thái hoặc vượt hạn mức','c','u','Từ chối; không ghi sổ')])
add('Là quản trị, tôi muốn xem doanh thu theo tháng để theo dõi kết quả kinh doanh.','HomeController',[
 ('1','a','u','Chọn năm/tháng cần xem'),('1.1','u','c','Gửi bộ lọc doanh thu'),('2','c','c','Kiểm tra quyền xem doanh thu'),('3','c','d','Lọc booking; tổng hợp theo tháng và trạng thái phù hợp'),('4','c','u','Trả dữ liệu bảng và biểu đồ')],[('2a','Không có quyền','c','u','Từ chối xem doanh thu'),('3a','Không có dữ liệu','c','u','Hiển thị tập rỗng hoặc chỉ số 0')])
for pause in [False,True]:
 add('Là quản trị, tôi muốn '+('tạm dừng nhận đặt tour để ngăn phát sinh đơn mới.' if pause else 'cho phép đặt tour để khách có thể đăng ký chuyến đi.'),'TourController',[
 ('1','a','u','Chọn tour; '+('tạm ngưng nhận đặt' if pause else 'cho phép nhận đặt')),('1.1','u','c','Gửi ID tour và trạng thái đích'),('2','c','d','Kiểm tra quyền duyệt, tour tồn tại và trạng thái hợp lệ'),('3','c','d','Lưu t_status = '+('2' if pause else '1')+'; ghi nhật ký xuất bản'),('4','c','u','Trả trạng thái mới; '+('cho xem nhưng không nhận booking' if pause else 'áp dụng hiển thị và nhận booking'))],[('2a','Không có quyền, tour không tồn tại hoặc mã trạng thái sai','c','u','Từ chối; không cập nhật tour')])
assert len(D)==26
# Draw deterministic sequence diagrams with branch references to specification.
fontpath='/System/Library/Fonts/Supplemental/Arial.ttf'
fontbold='/System/Library/Fonts/Supplemental/Arial Bold.ttf'
F=ImageFont.truetype(fontpath,29); FB=ImageFont.truetype(fontbold,30); SMALL=ImageFont.truetype(fontpath,26)
BLUE='#36577b'; INK='#172b43'; GRAY='#b6c1cf'
def wrap(t,font,maxw,draw):
 lines=[];line=''
 for word in t.split():
  n=(line+' '+word).strip()
  if draw.textlength(n,font=font)>maxw and line:lines.append(line);line=word
  else:line=n
 if line:lines.append(line)
 return lines

def diagram(n,o):
 actor='VNPay' if n==10 else ('Khách truy cập' if n<=5 else 'Khách hàng' if n<=12 else 'Đại lý' if n<=23 else 'Quản trị')
 lanes=[('a',actor),('u','Giao diện'),('c',o['controller']),('d','Dữ liệu nghiệp vụ')]
 if n==10:lanes=[('a','VNPay'),('c',o['controller']),('d','BookTour / VnpayPayment')]
 if o['external']:lanes.append(('x',o['external']))
 W=1800; xs={key:180+i*1440/(len(lanes)-1) for i,(key,_) in enumerate(lanes)}
 temp=Image.new('RGB',(W,100));td=ImageDraw.Draw(temp)
 rows=[]
 for num,a,b,t in o['main']:
  width=abs(xs[b]-xs[a])-35 if a!=b else 470
  width=max(250,width)
  lines=wrap(num+'. '+t,F,width,td)
  rows.append((num,a,b,t,lines,max(88,len(lines)*35+35),False))
 mainend=175+sum(r[5] for r in rows)
 branchrows=[]
 for num,guard,a,b,t in o['branches']:
  gl=wrap('opt ['+num+': '+guard+']',SMALL,1680,td)
  width=max(250,abs(xs[b]-xs[a])-35)
  lines=wrap(num+'. '+t,F,width,td)
  h=len(gl)*31+len(lines)*35+52
  branchrows.append((num,guard,a,b,t,gl,lines,h))
 H=mainend+65+sum(r[7] for r in branchrows)+35
 im=Image.new('RGB',(W,H),'white');dr=ImageDraw.Draw(im)
 for key,label in lanes:
  x=xs[key];bw=320 if key in ('c','d') else 260
  dr.rounded_rectangle((x-bw/2,10,x+bw/2,125),radius=9,fill='#eff3f8',outline=BLUE,width=2)
  lines=wrap(label,FB,bw-20,dr);dr.multiline_text((x,65),'\n'.join(lines),font=FB,fill=INK,anchor='mm',align='center',spacing=3)
  for yy in range(130,H-15,17):dr.line((x,yy,x,min(yy+9,H-15)),fill=GRAY,width=2)
 def arrow(a,b,lines,y,h):
  x1=xs[a];x2=xs[b];ay=y+h-18
  if a==b:
   dr.line([(x1,ay-18),(x1+65,ay-18),(x1+65,ay),(x1,ay)],fill=BLUE,width=2);end=x1;sign=-1
   dr.multiline_text((x1+80,y+3),'\n'.join(lines),font=F,fill=INK,spacing=3)
  else:
   dr.multiline_text(((x1+x2)/2,y+2),'\n'.join(lines),font=F,fill=INK,anchor='ma',align='center',spacing=3)
   dr.line((x1,ay,x2,ay),fill=BLUE,width=2);end=x2;sign=1 if x2>x1 else -1
  dr.polygon([(end,ay),(end-sign*13,ay-6),(end-sign*13,ay+6)],fill=BLUE)
 y=155
 for num,a,b,t,lines,h,_ in rows:arrow(a,b,lines,y,h);y+=h
 y=mainend+10
 dr.text((25,y),'Luồng phụ  •  Chỉ thực hiện khi điều kiện tương ứng xảy ra',font=SMALL,fill=INK);y+=48
 for num,guard,a,b,t,gl,lines,h in branchrows:
  dr.rectangle((15,y,W-15,y+h),outline=BLUE,width=2)
  gh=len(gl)*31+10
  dr.rectangle((16,y+1,W-16,y+gh),fill='#f2f5f9')
  dr.multiline_text((30,y+4),'\n'.join(gl),font=SMALL,fill=INK,spacing=0)
  arrow(a,b,lines,y+gh+4,h-gh-6);y+=h
 buf=BytesIO();im.save(buf,format='PNG');return buf.getvalue(),H

z=ZipFile('/Users/macair2020/Documents/PhanTichThietKeHeThong.docx')
ns={'w':'http://schemas.openxmlformats.org/wordprocessingml/2006/main','a':'http://schemas.openxmlformats.org/drawingml/2006/main','r':'http://schemas.openxmlformats.org/officeDocument/2006/relationships','wp':'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing'}
root=E.fromstring(z.read('word/document.xml'));body=list(root.find('w:body',ns));rels=E.fromstring(z.read('word/_rels/document.xml.rels'));rm={x.get('Id'):x.get('Target') for x in rels}
replacements={};uc=0

def settext(el,t):
 ts=el.xpath('.//w:t',namespaces=ns)
 if ts:ts[0].text=t
 for x in ts[1:]:x.text=''

for i,e in enumerate(body):
 txt=''.join(e.xpath('.//w:t/text()',namespaces=ns))
 if re.match(r'UC\d\d ',txt):uc=int(txt[2:4])
 if e.tag.endswith('}tbl') and 1<=uc<=26 and 'Luồng chính' in txt:
  for tr in e.findall('w:tr',ns):
   cells=tr.findall('w:tc',ns)
   if len(cells)<2:continue
   label=''.join(cells[0].xpath('.//w:t/text()',namespaces=ns))
   if label=='Tác nhân và mục tiêu':settext(cells[0],'User story');settext(cells[1],D[uc-1]['story'])
   if label=='Nhánh thay thế và ngoại lệ':
    # Match branch codes on the diagram and the specification.
    text='; '.join(f'{num}: {guard} — {t}' for num,guard,a,b,t in D[uc-1]['branches'] if not re.search(r'\.\d+$',num)) + '.'
    settext(cells[1],text)
 if 'Sequence diagram của' in txt and 1<=uc<=26:
  pic=body[i-1];rid=pic.xpath('.//a:blip/@r:embed',namespaces=ns)[0];target='word/'+rm[rid]
  data,h=diagram(uc,D[uc-1]);replacements[target]=data
  (ROOT/f'UC{uc:02}.png').write_bytes(data)
  # Preserve the original width; use the natural ratio, capped to the original image height to retain page layout.
  ext=pic.find('.//wp:extent',ns)
  if ext is not None:
   cx=int(ext.get('cx'));cy=int(ext.get('cy'));newcy=min(cy,round(cx*h/1800))
   # Detailed diagrams retain original height, including its full usable page area.
   ext.set('cy',str(newcy))
   for ex in pic.xpath('.//a:xfrm/a:ext',namespaces=ns):ex.set('cy',str(newcy))
  for dp in pic.xpath('.//wp:docPr',namespaces=ns):dp.set('descr',f'UC{uc:02} Sequence diagram. Số n khớp bước n của luồng chính; n.k là thông điệp chi tiết; na là nhánh thay thế tại bước n. Các nhánh được đối chiếu trong specification.')
# A short numbering legend before the use case index.
idx=next(i for i,e in enumerate(body) if e.tag.endswith('}tbl') and 'UC01' in ''.join(e.xpath('.//w:t/text()',namespaces=ns)))
p=E.Element('{'+ns['w']+'}p');r=E.SubElement(p,'{'+ns['w']+'}r');t=E.SubElement(r,'{'+ns['w']+'}t');t.text='Quy ước sequence diagram: n là bước n trong luồng chính; n.k là thông điệp chi tiết của bước n; na, nb là các nhánh phụ tại bước n; na.k là thông điệp tiếp theo trong nhánh. Nhánh phụ chỉ thực hiện khi điều kiện ghi trên khung xảy ra.'
root.find('w:body',ns).insert(idx,p)
replacements['word/document.xml']=E.tostring(root,xml_declaration=True,encoding='UTF-8',standalone=True)
out=ROOT/'PhanTichThietKeHeThong_da_chinh_sua.docx'
with ZipFile(out,'w',ZIP_DEFLATED) as dest:
 for info in z.infolist():dest.writestr(info,replacements.get(info.filename,z.read(info.filename)))
(ROOT/'changes.json').write_text(json.dumps(D,ensure_ascii=False,indent=2))
print(out)
