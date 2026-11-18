<div class="card mt-4">
    <div class="card-header"><h3 class="card-title">Đợt khởi hành cố định và số chỗ</h3></div>
    <div class="card-body">
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <p>Mỗi hành khách chiếm một chỗ. Tour cần đợt còn hạn và còn chỗ để nhận đặt.</p>
        @foreach($tour->schedules()->orderBy('ts_start_date')->get()->push(new \App\Models\TourSchedule) as $schedule)
            <div class="border rounded p-3 mb-3">
                <h4>{{ $schedule->exists ? 'Đợt #'.$schedule->id.' — Còn '.$schedule->remaining_seats.'/'.$schedule->ts_number_guests.' chỗ' : 'Thêm đợt khởi hành' }}</h4>
                <form method="post" action="{{ route('admin.tour.schedule.save', ['id'=>$tour->id, 'scheduleId'=>$schedule->exists ? $schedule->id : null]) }}">
                    @csrf
                    <div class="row">
                        @foreach(['ts_start_date'=>'Ngày đi','ts_end_date'=>'Ngày về'] as $field=>$label)
                            <div class="col-md-4 form-group"><label>{{ $label }}</label><input class="form-control" type="date" name="{{ $field }}" value="{{ substr($schedule->$field ?? '',0,10) }}" required></div>
                        @endforeach
                        @foreach(['ts_number_guests'=>'Số chỗ tối đa','adult_price'=>'Giá người lớn (VND)','child_price'=>'Giá trẻ em (VND)'] as $field=>$label)
                            <div class="col-md-4 form-group"><label>{{ $label }}</label><input class="form-control" type="number" name="{{ $field }}" min="{{ $field==='ts_number_guests'?1:0 }}" value="{{ $schedule->$field ?? ($field==='adult_price' ? $tour->t_price_adults : ($field==='child_price' ? $tour->t_price_children : '')) }}" required></div>
                        @endforeach
                        <div class="col-md-4 form-group"><label>Nhận đăng ký</label><select class="form-control" name="ts_status"><option value="1" @selected(($schedule->ts_status ?? 1)==1)>Mở</option><option value="0" @selected($schedule->exists && $schedule->ts_status==0)>Đóng</option></select></div>
                    </div>
                    <button class="btn btn-primary">Lưu đợt khởi hành</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
