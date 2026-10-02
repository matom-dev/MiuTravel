<form method="post" action="{{ route('agency.passenger',$booking->id) }}" class="border rounded p-3 mb-2">@csrf
    <input type="hidden" name="passenger_id" value="{{ $passenger->id }}">
    <div class="agency-fields"><label>Họ tên hành khách<input class="form-control" name="name" value="{{ $passenger->name }}" required></label><label>Kết quả tham gia<select class="form-control" name="attendance">@foreach(['pending'=>'Chưa ghi nhận','completed'=>'Đã hoàn thành','no_show'=>'Không tham gia'] as $key=>$label)<option value="{{ $key }}" @selected($passenger->attendance===$key)>{{ $label }}</option>@endforeach</select></label></div>
    <button class="btn btn-outline-primary btn-sm mt-2">{{ $passenger->exists?'Lưu hành khách':'Thêm hành khách' }}</button>
</form>
