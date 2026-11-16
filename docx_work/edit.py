from zipfile import ZipFile,ZIP_DEFLATED
from lxml import etree as E
from PIL import Image,ImageDraw,ImageFont
from io import BytesIO
from copy import deepcopy
import re
base='/Users/macair2020/Documents/Projects/docx_work/'
source='/Users/macair2020/Documents/PhanTichThietKeHeThong.docx'
ns={'w':'http://schemas.openxmlformats.org/wordprocessingml/2006/main','a':'http://schemas.openxmlformats.org/drawingml/2006/main','r':'http://schemas.openxmlformats.org/officeDocument/2006/relationships'}
w='{'+ns['w']+'}'
z=ZipFile(source);root=E.fromstring(z.read('word/document.xml'));body=root.find('w:body',ns);bs=list(body); rel={x.get('Id'):x.get('Target') for x in E.fromstring(z.read('word/_rels/document.xml.rels'))}
stories=[
'khách truy cập, tôi muốn lọc tour theo nhu cầu để tìm chuyến đi phù hợp.',
'khách truy cập, tôi muốn xem chi tiết tour để cân nhắc trước khi đặt.',
'khách truy cập, tôi muốn hỏi chatbot để được tư vấn tour phù hợp.',
'khách truy cập, tôi muốn xem lịch sử chatbot để tiếp tục cuộc tư vấn.',
'khách truy cập, tôi muốn đăng ký tài khoản để sử dụng các chức năng dành cho khách hàng.',
'khách hàng, tôi muốn đăng nhập để truy cập tài khoản và booking của mình.',
'khách hàng, tôi muốn đặt tour theo ngày và số khách để đăng ký chuyến đi.',
'khách hàng, tôi muốn xem booking của mình để theo dõi trạng thái đơn.',
'khách hàng, tôi muốn mở thanh toán VNPay cho booking để thanh toán trực tuyến.',
'hệ thống đặt tour, tôi muốn tiếp nhận và xác thực IPN VNPay để cập nhật kết quả thanh toán một lần.',
'khách hàng, tôi muốn xem kết quả VNPay đã xác thực để biết tình trạng thanh toán.',
'khách hàng, tôi muốn hủy booking còn chờ xác nhận để thay đổi kế hoạch chuyến đi.',
'đại lý, tôi muốn xem thống kê riêng của mình để theo dõi booking, thu tiền và doanh thu.',
'đại lý, tôi muốn tạo tour để chuẩn bị sản phẩm gửi quản trị duyệt xuất bản.',
'đại lý, tôi muốn sửa tour thuộc mình để cập nhật thông tin chuyến đi.',
'đại lý, tôi muốn tạo lịch khởi hành để mở các đợt đi với giá và sức chứa phù hợp.',
'đại lý, tôi muốn sửa lịch khởi hành để cập nhật kế hoạch mà vẫn bảo đảm các booking đã có.',
'đại lý, tôi muốn xác nhận booking đang chờ để tiếp nhận đơn của khách.',
'đại lý, tôi muốn đánh dấu booking đã xác nhận và đủ tiền là đã thanh toán để theo dõi xử lý đơn.',
'đại lý, tôi muốn hoàn tất booking đủ điều kiện sau chuyến đi để kết thúc xử lý đơn.',
'đại lý, tôi muốn hủy booking hợp lệ kèm lý do để xử lý đơn không thể thực hiện.',
'đại lý, tôi muốn ghi nhận khoản thu của booking để theo dõi số tiền thực nhận.',
'đại lý, tôi muốn ghi nhận khoản hoàn cho booking đã hủy để đối soát tiền với khách.',
'quản trị, tôi muốn xem doanh thu theo tháng để theo dõi kết quả kinh doanh.',
'quản trị, tôi muốn cho phép đặt tour để khách có thể tạo booking.',
'quản trị, tôi muốn tạm dừng nhận đặt tour để ngăn booking mới khi cần.'
]
# Mỗi nhãn đặt cùng hàng với thông điệp; phần trước dấu chấm tham chiếu bước Specification.
rows={
1:[(247,'1'),(337,'1.1'),(427,'2'),(553,'2.1'),(643,'2.2'),(733,'3'),(823,'3.1'),(913,'3a')],
2:[(247,'1'),(337,'1.1'),(427,'2'),(553,'2.1'),(643,'2.2'),(733,'3'),(823,'3.1'),(913,'2a')],
3:[(247,'1'),(373,'1.1'),(499,'2'),(589,'2.1'),(679,'2.2'),(769,'3'),(859,'3.1'),(949,'4'),(1039,'4.1'),(1129,'3a')],
4:[(247,'1'),(337,'2'),(427,'3'),(517,'3.1'),(607,'3.2'),(697,'3.3')],
5:[(283,'1'),(373,'1.1'),(499,'2'),(589,'2.1'),(679,'3'),(769,'3.1'),(859,'4'),(1003,'4.1'),(1111,'2a')],
6:[(247,'1'),(337,'2'),(427,'3'),(517,'3.1'),(607,'3.2'),(697,'4'),(787,'4.1'),(877,'3a'),(967,'3a.1')],
7:[(283,'1'),(409,'1.1'),(535,'2'),(661,'3'),(787,'3.1'),(954,'4'),(1080,'5'),(1170,'5.1'),(1296,'5.2'),(1413,'3a'),(1548,'3a.1')],
8:[(247,'1'),(337,'1.1'),(427,'2'),(517,'3'),(607,'3.1'),(697,'3a')],
9:[(247,'1'),(337,'1.1'),(463,'2'),(589,'2.1'),(797,'3'),(923,'4'),(1049,'4.1'),(1139,'5'),(1229,'2a'),(1319,'2a.1')],
10:[(283,'1'),(373,'2'),(499,'2.1'),(589,'3'),(679,'4'),(769,'4.1'),(859,'5'),(949,'5.1'),(1075,'2a'),(1165,'2a.1')],
11:[(247,'1'),(337,'2'),(427,'2.1'),(517,'3'),(607,'3.1'),(697,'3.2'),(787,'2a')],
12:[(283,'1'),(373,'1.1'),(463,'2'),(553,'2.1'),(679,'3'),(805,'3.1'),(895,'3.2'),(985,'4'),(1111,'4.1'),(1237,'2a')],
13:[(247,'1'),(337,'1.1'),(463,'2'),(589,'3'),(679,'3.1'),(769,'4'),(931,'4.1'),(1021,'4.2')],
14:[(247,'1'),(337,'1.1'),(463,'2'),(553,'2.1'),(643,'3'),(733,'3.1'),(823,'4'),(913,'2a')],
15:[(247,'1'),(337,'1.1'),(463,'2'),(553,'2.1'),(643,'3'),(733,'3.1'),(823,'4'),(913,'2a')],
16:[(283,'1'),(373,'1.1'),(499,'2'),(589,'2.1'),(679,'3'),(769,'4'),(859,'4.1'),(949,'4.2'),(1075,'3a')],
17:[(283,'1'),(373,'1.1'),(499,'2'),(589,'2.1'),(679,'3'),(769,'4'),(859,'4.1'),(949,'4.2'),(1075,'3a')],
18:[(247,'1'),(337,'1.1'),(463,'2'),(589,'3'),(715,'3.1'),(841,'3.2'),(967,'4'),(1057,'5'),(1147,'5.1'),(1273,'3a'),(1399,'3a.1')],
19:[(247,'1'),(337,'1.1'),(463,'2'),(589,'3'),(715,'3.1'),(882,'3.2'),(1008,'4'),(1098,'5'),(1188,'5.1'),(1314,'3a'),(1440,'3a.1')],
20:[(247,'1'),(337,'1.1'),(463,'2'),(589,'3'),(715,'3.1'),(882,'3.2'),(972,'4'),(1062,'5'),(1152,'5.1'),(1278,'3a'),(1404,'3a.1')],
21:[(247,'1'),(337,'1.1'),(463,'2'),(589,'3'),(715,'3.1'),(841,'3.2'),(931,'4'),(1021,'5'),(1111,'5.1'),(1237,'3a'),(1363,'3a.1')],
22:[(247,'1'),(337,'2'),(463,'3'),(553,'3.1'),(643,'4'),(733,'4.1'),(823,'4.2'),(949,'3a')],
23:[(247,'1'),(337,'2'),(463,'3'),(553,'3.1'),(643,'4'),(733,'4.1'),(823,'4.2'),(949,'3a')],
24:[(247,'1'),(337,'1.1'),(463,'2'),(553,'3'),(643,'3.1'),(733,'3.2'),(895,'4'),(985,'4.1')],
25:[(283,'1'),(373,'1.1'),(463,'2'),(553,'2.1'),(679,'3'),(769,'3.1'),(859,'4'),(949,'2a')],
26:[(283,'1'),(373,'1.1'),(463,'2'),(553,'2.1'),(679,'3'),(769,'3.1'),(859,'4'),(949,'2a')]
}
font=ImageFont.truetype('/System/Library/Fonts/Supplemental/Arial Bold.ttf',29)
replacements={};n=0
for i,b in enumerate(bs):
 t=''.join(b.xpath('.//w:t/text()',namespaces=ns))
 if 'Sequence diagram của' not in t:continue
 n+=1;pic=bs[i-1];rid=pic.xpath('.//a:blip/@r:embed',namespaces=ns)[0];path='word/'+rel[rid]
 im=Image.open(BytesIO(z.read(path))).convert('RGB');c=Image.new('RGB',(im.width+150,im.height),'white');c.paste(im,(150,0));d=ImageDraw.Draw(c)
 for y,label in rows[n]:
  y=min(y,im.height-26);branch='a' in label;color='#8b341e' if branch else '#194c77'
  d.rounded_rectangle((8,y-24,125,y+16),radius=7,fill='#f7f7f7',outline=color,width=2)
  d.text((66,y-4),label,font=font,fill=color,anchor='mm');d.line((126,y,154,y),fill=color,width=2)
 buf=BytesIO();c.save(buf,format='PNG');replacements[path]=buf.getvalue()
 # Add story immediately before Specification heading, retain heading and page settings.
 start=i-1
 while start>=0 and not re.match(r'UC\d+ ',''.join(bs[start].xpath('.//w:t/text()',namespaces=ns))):start-=1
 h=bs[start];p=E.Element(w+'p');pp=E.SubElement(p,w+'pPr');sp=E.SubElement(pp,w+'spacing');sp.set(w+'after','100');r=E.SubElement(p,w+'r');rp=E.SubElement(r,w+'rPr');E.SubElement(rp,w+'b');E.SubElement(r,w+'t').text='User story: ';r=E.SubElement(p,w+'r');E.SubElement(r,w+'t').text='Là '+stories[n-1];body.insert(body.index(h)+1,p)
# Shared legend after scope table, before UC01.
p=E.Element(w+'p');r=E.SubElement(p,w+'r');E.SubElement(r,w+'t').text='Quy ước đánh số Sequence diagram: n tương ứng bước n của luồng chính trong Specification; n.1, n.2 là các thông điệp chi tiết của cùng bước; na là nhánh thay thế hoặc ngoại lệ tại bước n, na.1 là thông điệp tiếp theo trong nhánh. Các nhãn ở lề trái nằm cùng hàng với thông điệp tương ứng.'
body.insert(body.index(bs[51]),p)
# Specify which numbered branch the existing alternative represents, preserving original specification.
branch={1:'3a Không có kết quả: trả danh sách rỗng.',2:'2a Tour không tồn tại hoặc không công khai: báo không tìm thấy.',3:'3a Thiếu cấu hình hoặc lỗi AI: dùng phản hồi dự phòng, giữ tin đã lưu.',5:'2a Email trùng hoặc dữ liệu sai: trả lỗi; lỗi lưu phải rollback.',6:'3a Sai thông tin hoặc vượt giới hạn thử: không tạo phiên.',7:'3a Tour/lịch/chỗ không hợp lệ: rollback, không tạo booking.',8:'3a Chưa có booking: trả danh sách rỗng.',9:'2a Không đủ điều kiện: từ chối khởi tạo thanh toán.',10:'2a Từ chối IPN sai hoặc đã xử lý; trả mã 97, 01, 04, 02 hoặc 99 theo Specification.',11:'2a Tham số sai hoặc chưa xác định: hướng dẫn kiểm tra booking.',12:'2a Sai chủ, sai trạng thái hoặc thiếu lý do: không hủy booking.',14:'2a Sai sở hữu hoặc dữ liệu/trạng thái không hợp lệ: từ chối.',15:'2a Sai sở hữu hoặc dữ liệu/trạng thái không hợp lệ: từ chối.',16:'3a Trùng ngày, sai sức chứa/nhân sự hoặc dữ liệu không hợp lệ: hoàn tác.',17:'3a Trùng ngày, sai sức chứa/nhân sự hoặc dữ liệu không hợp lệ: hoàn tác.',18:'3a Sai quyền hoặc điều kiện chuyển: không đổi trạng thái.',19:'3a Sai quyền hoặc điều kiện chuyển: không đổi trạng thái.',20:'3a Sai quyền hoặc điều kiện chuyển: không đổi trạng thái.',21:'3a Sai quyền hoặc điều kiện chuyển: không đổi trạng thái.',22:'3a Trùng tham chiếu, sai trạng thái hoặc vượt hạn mức: không ghi sổ.',23:'3a Trùng tham chiếu, sai trạng thái hoặc vượt hạn mức: không ghi sổ.',25:'2a Không có quyền, tour không tồn tại hoặc trạng thái sai: từ chối.',26:'2a Không có quyền, tour không tồn tại hoặc trạng thái sai: từ chối.'}
n=0
for b in bs:
 if 'Sequence diagram của' in ''.join(b.xpath('.//w:t/text()',namespaces=ns)):
  n+=1
  if n in branch:
   p=E.Element(w+'p');r=E.SubElement(p,w+'r');E.SubElement(r,w+'t').text='Nhánh '+branch[n];body.insert(body.index(b)+1,p)
replacements['word/document.xml']=E.tostring(root,xml_declaration=True,encoding='UTF-8',standalone=True)
out='/Users/macair2020/Documents/Projects/PhanTichThietKeHeThong_DaCapNhat.docx'
with ZipFile(out,'w',ZIP_DEFLATED) as dest:
 for info in z.infolist():dest.writestr(info,replacements.get(info.filename,z.read(info.filename)))
print(out, 'stories',n,'numbered diagrams',len(replacements)-1)
