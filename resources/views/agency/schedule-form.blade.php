<form method="post" action="{{ $schedule->exists ? route('agency.schedule.update',[$tour->id,$schedule->id]) : route('agency.schedule.create',$tour->id) }}">@csrf
    <div class="agency-fields">
        @foreach(['ts_start_date'=>'Ngày đi','ts_end_date'=>'Ngày về','registration_deadline'=>'Hạn đăng ký'] as $field=>$label)
            <label>{{ $label }}<input class="form-control" type="date" name="{{ $field }}" value="{{ old($field,substr($schedule->$field??'',0,10)) }}" required></label>
        @endforeach
        @foreach(['ts_number_guests'=>'Sức chứa','adult_price'=>'Giá người lớn','child_price'=>'Giá trẻ em'] as $field=>$label)
            <label>{{ $label }} ({{ $field==='ts_number_guests'?'khách':'VND' }})<input class="form-control" type="number" name="{{ $field }}" value="{{ old($field,$schedule->$field) }}" min="{{ $field==='ts_number_guests'?1:0 }}" required></label>
        @endforeach
        <label>Nhận đăng ký<select class="form-control" name="ts_status"><option value="1" @selected((string)old('ts_status',$schedule->ts_status??1)==='1')>Mở</option><option value="0" @selected((string)old('ts_status',$schedule->ts_status??1)==='0')>Đóng</option></select></label>
    </div>
    <button class="btn btn-primary mt-3"><i class="fas fa-save mr-1"></i> Lưu đợt khởi hành</button>
</form>
