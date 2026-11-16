<form method="post" action="{{ $schedule->exists ? route('agency.schedule.update',[$tour->id,$schedule->id]) : route('agency.schedule.create',$tour->id) }}">@csrf
    <div class="agency-fields">
        @foreach(['ts_start_date'=>'Ngày đi','ts_end_date'=>'Ngày về','registration_deadline'=>'Hạn đăng ký'] as $field=>$label)
            <label>{{ $label }}<input class="form-control" type="date" name="{{ $field }}" value="{{ old($field,substr($schedule->$field??'',0,10)) }}" required></label>
        @endforeach
        @foreach(['ts_number_guests'=>'Số chỗ tối đa','adult_price'=>'Giá người lớn','child_price'=>'Giá trẻ em'] as $field=>$label)
            <label>{{ $label }} ({{ $field==='ts_number_guests'?'khách':'VND' }})<input class="form-control" type="number" name="{{ $field }}" value="{{ old($field,$schedule->$field ?? ($field === 'adult_price' ? $tour->t_price_adults : ($field === 'child_price' ? $tour->t_price_children : null))) }}" min="{{ $field==='ts_number_guests'?1:0 }}" required></label>
        @endforeach
        <label>Nhận đăng ký<select class="form-control" name="ts_status"><option value="1" @selected((string)old('ts_status',$schedule->ts_status??1)==='1')>Mở</option><option value="0" @selected((string)old('ts_status',$schedule->ts_status??1)==='0')>Đóng</option></select></label>
    </div>
    <input type="hidden" name="update_assignments" value="1">
    @php $availableGuides = $tour->staffGuides->merge($ownGuides ?? collect())->unique('id')->where('tg_status', 1); @endphp
    <div class="agency-fields mt-3">
        @foreach(['guide'=>'Hướng dẫn viên', 'leader'=>'Trưởng đoàn'] as $role=>$label)
            <label>{{ $label }} của đợt
                <select class="form-control" name="{{ $role }}_ids[]" multiple>
                    @foreach($availableGuides->whereIn('tg_role', [$role, 'both']) as $guide)
                        <option value="{{ $guide->id }}" @selected($schedule->guideAssignments->where('role', $role)->contains('tour_guide_id', $guide->id))>{{ $guide->tg_name }}</option>
                    @endforeach
                </select>
            </label>
        @endforeach
    </div>
    <small class="text-muted">Để trống cả hai mục để dùng nhân sự chung của tour.</small>
    <button class="btn btn-primary mt-3"><i class="fas fa-save mr-1"></i> Lưu đợt khởi hành</button>
</form>
