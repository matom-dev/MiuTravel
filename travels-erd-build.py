from pathlib import Path
import json,re,html
out=Path('/Users/macair2020/Documents/Projects/Travels-design'); out.mkdir(exist_ok=True)
raw='''
users|id:B! name:V! email:V! phone:V email_verified_at:T password:V! avatar:V address:V status:tinyint(4)! remember_token:varchar(100) created_at:T updated_at:T
agencies|id:B! user_id:B! name:V! logo:V description:text address:V phone:V email:V verification_information:text verification_status:V! active:tinyint(1)! commission_basis_points:int(10)! created_at:T updated_at:T
tours|id:B! t_title:V t_journeys:V t_schedule:V t_duration_days:smallint(5) t_duration_nights:smallint(5) t_move_method:V t_starting_gate:V t_start_date:date t_end_date:date t_number_guests:int(11)! t_price_adults:int(11)! t_price_children:int(11)! t_sale:int(11)! t_view:int(11)! t_description:text t_content:text t_activities:longtext t_guides:longtext t_anbum_image:text t_image:V t_location_id:B t_type:varchar(30) t_user_id:B t_number_registered:int(11) t_follow:int(11) t_status:tinyint(4)! created_at:T updated_at:T agency_id:B cancellation_policy:text
tour_schedules|id:B! ts_tour_id:B! ts_start_date:datetime! ts_end_date:datetime! ts_number_guests:int(10)! ts_number_registered:int(10)! ts_follow:int(10)! ts_status:tinyint(3)! created_at:T updated_at:T adult_price:int(10) child_price:int(10) registration_deadline:date departure_day:date
book_tours|id:B! b_code:varchar(30) b_tour_id:B b_tour_schedule_id:B b_user_id:B b_assigned_staff_id:B b_name:V b_email:varchar(100) b_phone:varchar(100) b_address:varchar(100) b_start_date:date b_end_date:datetime b_note:text b_cancel_reason:text b_internal_note:text b_number_adults:int(11) b_number_children:int(11) b_number_child6:int(11)! b_price_child2:int(11)! b_number_child2:int(11)! b_price_child6:int(11)! b_price_adults:int(11)! b_price_children:int(11)! b_status:tinyint(4) created_at:T updated_at:T policy_snapshot:text commission_basis_points:int(10)! revenue_recognized_at:T agency_id:B
booking_passengers|id:B! book_tour_id:B! name:V! attendance:V! created_at:T updated_at:T
booking_status_histories|id:B! book_tour_id:B! old_status:tinyint(3) new_status:tinyint(3)! changed_by:B changed_guard:varchar(30) note:text created_at:T updated_at:T
vnpay_payments|id:B! book_tour_id:B! reference:varchar(32)! amount:B! status:V! transaction_no:V response_code:varchar(2) bank_code:V expires_at:T! created_at:T updated_at:T
agency_transactions|id:B! book_tour_id:B! recorded_by:B! type:V! amount:B! reference:V! note:text! occurred_at:T! created_at:T updated_at:T
tour_guides|id:B! tg_name:V! tg_role:varchar(30)! tg_gender:varchar(20) tg_birth_date:date tg_hometown:V tg_phone:varchar(30) tg_email:V tg_experience:V tg_languages:V tg_photo:V tg_status:tinyint(3)! tg_user_id:B created_at:T updated_at:T
tour_guide_assignments|id:B! tga_tour_id:B! tga_guide_id:B! tga_role:varchar(30)! created_at:T updated_at:T
schedule_guide_assignments|id:B! tour_schedule_id:B! tour_guide_id:B! role:varchar(30)! created_at:T updated_at:T
locations|id:B! l_name:V l_slug:V l_image:V l_description:V l_content:text l_status:tinyint(4) l_user_id:B created_at:T updated_at:T
hotels|id:B! h_name:V h_image:V h_address:V h_phone:V h_accommodation_type:varchar(30)! h_star_rating:tinyint(3) h_amenities:longtext h_room_facilities:longtext h_property_policies:longtext h_meal_plans:longtext h_suitable_for:longtext h_anbum_image:text h_description:text h_content:text h_status:tinyint(4)! h_location_id:B h_user_id:B created_at:T updated_at:T
car_rentals|id:B! cr_name:V! cr_image:V cr_album_images:longtext cr_location_id:B cr_user_id:B cr_vehicle_type:V cr_driver_option:varchar(30) cr_number_seats:smallint(5) cr_transmission:varchar(50) cr_fuel:varchar(50) cr_price:decimal(15,2)! cr_phone:varchar(30) cr_address:V cr_description:text cr_content:longtext cr_status:tinyint(4)! created_at:T updated_at:T
categories|id:B! c_name:V! c_parent_id:B c_slug:V! c_avatar:V c_banner:V c_description:V c_hot:tinyint(4)! c_status:tinyint(4)! c_type:tinyint(4) c_user_id:B created_at:T updated_at:T
articles|id:B! a_title:V! a_slug:V! a_show_home:tinyint(4)! a_active:tinyint(4)! a_view:int(11)! a_description:text a_avatar:V a_album_images:longtext a_content:text a_category_id:B a_user_id:B created_at:T updated_at:T
comments|id:B! cm_reply_id:B cm_user_id:B cm_article_id:B cm_hotel_id:B cm_tour_id:B cm_content:text cm_rating:tinyint(3) cm_images:text cm_status:tinyint(4) created_at:T updated_at:T
contacts|id:B! name:V email:V subject:V message:text c_user_id:B created_at:T updated_at:T
chat_messages|id:B! user_id:B guest_token:V sender:enum(user,bot)! message:text! created_at:T updated_at:T
app_notifications|id:B! receiver_guard:varchar(20)! receiver_id:B type:varchar(60)! title:V! message:text url:V data:longtext read_at:T created_at:T updated_at:T
admin_audit_logs|id:B! actor_id:B actor_guard:varchar(30)! action:varchar(80)! subject_type:V subject_id:B metadata:longtext ip_address:varchar(45) user_agent:V created_at:T updated_at:T
roles|id:B! name:V! display_name:V description:V created_at:T updated_at:T
role_user|role_id:B! user_id:B!
permissions|id:B! name:V! display_name:V description:V group_permission_id:B! created_at:T updated_at:T
permission_role|permission_id:B! role_id:B!
group_permission|id:B! name:V! description:V created_at:T updated_at:T
personal_access_tokens|id:B! tokenable_type:V! tokenable_id:B! name:V! token:varchar(64)! abilities:text last_used_at:T expires_at:T created_at:T updated_at:T
password_resets|email:V! token:V! created_at:T
jobs|id:B! queue:V! payload:longtext! attempts:tinyint(3)! reserved_at:int(10) available_at:int(10)! created_at:int(10)!
failed_jobs|id:B! uuid:V! connection:text! queue:text! payload:longtext! exception:longtext! failed_at:T!
migrations|id:int(10)! migration:V! batch:int(11)!
database_integrity_archives|id:B! source_table:varchar(64)! source_id:B! reason:V! original_record:longtext! archived_at:T!
'''
edges='''
admin_audit_logs actor_id users
agencies user_id users
agency_transactions book_tour_id book_tours
agency_transactions recorded_by users
app_notifications receiver_id users
articles a_category_id categories
articles a_user_id users
booking_passengers book_tour_id book_tours
booking_status_histories book_tour_id book_tours
booking_status_histories changed_by users
book_tours agency_id agencies
book_tours b_assigned_staff_id users
book_tours b_tour_id tours
book_tours b_tour_schedule_id tour_schedules
book_tours b_user_id users
book_tours b_tour_schedule_id,b_tour_id tour_schedules id,ts_tour_id
car_rentals cr_location_id locations
car_rentals cr_user_id users
categories c_parent_id categories
categories c_user_id users
chat_messages user_id users
comments cm_article_id articles
comments cm_hotel_id hotels
comments cm_reply_id comments
comments cm_tour_id tours
comments cm_user_id users
contacts c_user_id users
hotels h_location_id locations
hotels h_user_id users
locations l_user_id users
permissions group_permission_id group_permission
permission_role permission_id permissions
permission_role role_id roles
role_user role_id roles
role_user user_id users
schedule_guide_assignments tour_guide_id tour_guides
schedule_guide_assignments tour_schedule_id tour_schedules
tours agency_id agencies
tours t_location_id locations
tours t_user_id users
tour_guides tg_user_id users
tour_guide_assignments tga_guide_id tour_guides
tour_guide_assignments tga_tour_id tours
tour_schedules ts_tour_id tours
vnpay_payments book_tour_id book_tours
'''
tables=[]
for line in raw.strip().splitlines():
    name,cs=line.split('|'); cols=[]
    for col in cs.split():
        n,t=col.split(':'); nn=t.endswith('!'); t=t.rstrip('!'); t={'B':'bigint(20) unsigned','V':'varchar(255)','T':'timestamp'}.get(t,t)
        cols.append(dict(name=n,type=t,nullable=not nn))
    pk=['id'] if name!='password_resets' else []
    if name=='role_user': pk=['user_id','role_id']
    if name=='permission_role': pk=['permission_id','role_id']
    tables.append(dict(name=name,cols=cols,pk=pk))
es=[]
for line in edges.strip().splitlines():
    p=line.split(); es.append(dict(child=p[0],cols=p[1].split(','),parent=p[2],refs=p[3].split(',') if len(p)>3 else ['id']))
by={t['name']:t for t in tables}
unsigned={'tours':['t_duration_days','t_duration_nights'],'tour_schedules':['ts_number_guests','ts_number_registered','ts_follow','ts_status','adult_price','child_price'],'book_tours':['commission_basis_points'],'agencies':['commission_basis_points'],'booking_status_histories':['old_status','new_status'],'car_rentals':['cr_number_seats'],'hotels':['h_star_rating'],'comments':['cm_rating'],'tour_guides':['tg_status'],'jobs':['attempts','reserved_at','available_at','created_at'],'migrations':['id']}
for t in tables:
    for c in t['cols']:
        if c['name'] in unsigned.get(t['name'],[]): c['type']+=' unsigned'
        c['pk']=c['name'] in t['pk']; c['fk']=any(e['child']==t['name'] and c['name'] in e['cols'] for e in es)
uk={'users':['email'],'agencies':['user_id'],'book_tours':['b_code'],'categories':['c_slug'],'agency_transactions':['reference'],'vnpay_payments':['reference','transaction_no'],'roles':['name'],'permissions':['name'],'group_permission':['name'],'personal_access_tokens':['token'],'failed_jobs':['uuid']}
for t in tables:
    for c in t['cols']: c['uk']=c['name'] in uk.get(t['name'],[])
data=dict(tables=tables,edges=es)
(out/'schema.json').write_text(json.dumps(data,ensure_ascii=False,indent=2))
def mermaid(names):
    lines=['erDiagram']
    for t in tables:
        if t['name'] not in names: continue
        lines.append('    '+t['name']+' {')
        for c in t['cols']:
            keys=[k.upper() for k in ['pk','fk','uk'] if c[k]]
            typ=re.sub(r'[^a-zA-Z0-9_]','_',c['type']).strip('_')
            lines.append('        '+typ+' '+c['name']+(' '+','.join(keys) if keys else '')+' "'+('NULL' if c['nullable'] else 'NOT NULL')+'"')
        lines.append('    }')
    for e in es:
        if e['child'] not in names or e['parent'] not in names: continue
        nullable=any(next(c for c in by[e['child']]['cols'] if c['name']==n)['nullable'] for n in e['cols'])
        left='|o' if nullable else '||'; right='o|' if e['child']=='agencies' else 'o{'
        lines.append(f'    {e["parent"]} {left}--{right} {e["child"]} : "{",".join(e["cols"])} → {",".join(e["refs"])}"')
    return '\n'.join(lines)+'\n'
(out/'Travels-full.mmd').write_text(mermaid(set(by)))
groups={'tour-booking':['users','agencies','locations','tours','tour_schedules','book_tours','booking_passengers','booking_status_histories','agency_transactions','vnpay_payments','tour_guides','tour_guide_assignments','schedule_guide_assignments'],'content-services':['users','categories','articles','comments','locations','tours','hotels','car_rentals','contacts','chat_messages','app_notifications','admin_audit_logs'],'permissions':['users','roles','role_user','permissions','permission_role','group_permission']}
for n,ns in groups.items(): (out/(n+'.mmd')).write_text(mermaid(set(ns)))
(out/'README.md').write_text('''# Travels — thiết kế database hiện tại

Nguồn: SHOW CREATE TABLE của 33 bảng trên phpMyAdmin localhost, ngày 07/10/2026. Sơ đồ chỉ nối các FOREIGN KEY đã khai báo.

- `Travels-full.mmd`: ERD đầy đủ, tất cả cột, PK, FK và các UNIQUE một cột.
- Các bản theo nhóm: tour-booking, content-services, permissions.
- `schema.json`: danh sách cột và quan hệ để tái sử dụng. Không chứa bản ghi dữ liệu.

PK = khóa chính; FK = khóa ngoại; UK = unique một cột. Kiểu dữ liệu trong Mermaid dùng dấu gạch dưới thay dấu ngoặc/khoảng trắng. Nullability hiển thị trên từng cột. Sơ đồ không liệt kê default, index thường hay hành vi ON DELETE/UPDATE.

## Các ràng buộc ghép

- `permission_role`: PRIMARY KEY (permission_id, role_id).
- `role_user`: PRIMARY KEY (user_id, role_id).
- `tour_schedules`: UNIQUE (id, ts_tour_id), UNIQUE (ts_tour_id, departure_day).
- `tour_guide_assignments`: UNIQUE (tga_tour_id, tga_guide_id, tga_role).
- `schedule_guide_assignments`: UNIQUE (tour_schedule_id, tour_guide_id, role).
- `database_integrity_archives`: UNIQUE (source_table, source_id).
- `book_tours`: FOREIGN KEY (b_tour_schedule_id, b_tour_id) → tour_schedules (id, ts_tour_id), bên cạnh các FK một cột.

`departure_day` là cột sinh VIRTUAL từ DATE(ts_start_date). `password_resets` không có PRIMARY KEY. `personal_access_tokens.tokenable_type/tokenable_id` là cặp tham chiếu đa hình, không có FOREIGN KEY. Các cặp subject_type/subject_id và source_table/source_id cũng không có FOREIGN KEY được khai báo.
''')
print(f'{len(tables)} tables, {len(es)} foreign keys, {sum(len(t["cols"]) for t in tables)} columns')
