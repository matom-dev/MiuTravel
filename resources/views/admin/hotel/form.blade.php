@php
    $selectedAmenities = old('h_amenities', isset($hotel) ? ($hotel->h_amenities ?? []) : []);
    $selectedRoomFacilities = old('h_room_facilities', isset($hotel) ? ($hotel->h_room_facilities ?? []) : []);
    $selectedPropertyPolicies = old('h_property_policies', isset($hotel) ? ($hotel->h_property_policies ?? []) : []);
    $selectedMealPlans = old('h_meal_plans', isset($hotel) ? ($hotel->h_meal_plans ?? []) : []);
    $selectedSuitableFor = old('h_suitable_for', isset($hotel) ? ($hotel->h_suitable_for ?? []) : []);
@endphp
<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h4 class="card-title font-weight-bold text-primary mb-3">Thông tin Khách sạn</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group {{ $errors->first('h_name') ? 'has-error' : '' }} mb-4">
                            <label for="h_name" class="control-label font-weight-bold text-muted">Tên khách sạn <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="text" maxlength="100" class="form-control px-3 py-2" id="h_name" placeholder="Nhập tên khách sạn..." name="h_name" value="{{ old('h_name',isset($hotel) ? $hotel->h_name : '') }}" style="border-radius: 5px;">
                                @if($errors->has('h_name'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('h_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Loại hình lưu trú <sup class="text-danger">(*)</sup></label>
                                    <select class="form-control custom-select px-3 py-2" name="h_accommodation_type" style="border-radius: 5px;">
                                        @foreach(\App\Models\Hotel::ACCOMMODATION_TYPES as $key => $label)
                                            <option value="{{ $key }}" {{ old('h_accommodation_type', isset($hotel) ? $hotel->h_accommodation_type : 'hotel') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('h_accommodation_type'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('h_accommodation_type') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Hạng khách sạn</label>
                                    <select class="form-control custom-select px-3 py-2" name="h_star_rating" style="border-radius: 5px;">
                                        <option value="">Chưa xác minh hạng sao</option>
                                        @foreach(range(1, 5) as $star)
                                            <option value="{{ $star }}" {{ (string) old('h_star_rating', isset($hotel) ? $hotel->h_star_rating : '') === (string) $star ? 'selected' : '' }}>
                                                {{ $star }} sao
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('h_star_rating'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('h_star_rating') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-7 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted d-block">Tiện nghi đã xác minh</label>
                                    <div class="row">
                                        @foreach(\App\Models\Hotel::AMENITIES as $key => $label)
                                            <div class="col-sm-6 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="amenity-{{ $key }}"
                                                        name="h_amenities[]" value="{{ $key }}"
                                                        {{ in_array($key, $selectedAmenities, true) ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-normal" for="amenity-{{ $key }}">{{ $label }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted d-block">Phù hợp với nhóm khách</label>
                                    @foreach(\App\Models\Hotel::SUITABLE_FOR as $key => $label)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="suitable-{{ $key }}"
                                                name="h_suitable_for[]" value="{{ $key }}"
                                                {{ in_array($key, $selectedSuitableFor, true) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal" for="suitable-{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted d-block">Tiện nghi phòng</label>
                                    @foreach(\App\Models\Hotel::ROOM_FACILITIES as $key => $label)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="room-facility-{{ $key }}"
                                                name="h_room_facilities[]" value="{{ $key }}"
                                                {{ in_array($key, $selectedRoomFacilities, true) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal" for="room-facility-{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted d-block">Chính sách lưu trú</label>
                                    @foreach(\App\Models\Hotel::PROPERTY_POLICIES as $key => $label)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="property-policy-{{ $key }}"
                                                name="h_property_policies[]" value="{{ $key }}"
                                                {{ in_array($key, $selectedPropertyPolicies, true) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal" for="property-policy-{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted d-block">Bữa ăn & dịch vụ</label>
                                    @foreach(\App\Models\Hotel::MEAL_PLANS as $key => $label)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="meal-plan-{{ $key }}"
                                                name="h_meal_plans[]" value="{{ $key }}"
                                                {{ in_array($key, $selectedMealPlans, true) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal" for="meal-plan-{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Địa chỉ chi tiết <sup class="text-danger">(*)</sup></label>
                                    <input type="text" class="form-control px-3 py-2" placeholder="Ví dụ: 20 Quách Xuân Kỳ, Đồng Hới, Quảng Bình" name="h_address" value="{{ old('h_address',isset($hotel) ? $hotel->h_address : '') }}" style="border-radius: 5px;">
                                    @if($errors->has('h_address'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('h_address') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Trạng thái</label>
                                    <select class="form-control custom-select px-3 py-2" name="h_status" style="border-radius: 5px;">
                                        @foreach($status as $key => $statu)
                                            <option {{old('h_status', isset($hotel->h_status ) ? $hotel->h_status : '') == $key ? 'selected="selected"' : ''}} value="{{$key}}">
                                                {{$statu}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('h_status'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('h_status') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Số điện thoại lễ tân <sup class="text-danger">(*)</sup></label>
                                    <div>
                                        <input type="text" class="form-control px-3 py-2" placeholder="Nhập số điện thoại khách hàng sẽ gọi trực tiếp..." name="h_phone" value="{{ old('h_phone',isset($hotel) ? $hotel->h_phone : '') }}" style="border-radius: 5px;">
                                        @if($errors->has('h_phone'))
                                            <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('h_phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group {{ $errors->first('h_content') ? 'has-error' : '' }} mb-4">
                            <label for="h_content" class="control-label font-weight-bold text-muted">Nội dung khách sạn <sup class="text-danger">(*)</sup></label>
                            <div>
                                <textarea name="h_content" id="h_content" cols="20" rows="10" style="resize:vertical; height: 420px;" class="form-control" placeholder="Giới thiệu khách sạn, tiện nghi, vị trí và chính sách lưu trú...">{{ old('h_content', isset($hotel) ? ($hotel->h_content ?: $hotel->h_description) : '') }}</textarea>
                                @if($errors->has('h_content'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('h_content') }}</span>
                                @endif
                                <script>
                                    ckeditorArticle(h_content);
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 admin-sticky-sidebar">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-cog text-muted mr-1"></i> Hành động</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2" style="gap: 10px;">
                            <button type="submit" name="submit" class="btn btn-primary w-100 py-2 font-weight-bold rounded">
                                <i class="fas fa-save mr-1"></i> Lưu dữ liệu
                            </button>
                            <button type="reset" name="reset" value="reset" class="btn btn-outline-secondary w-100 py-2 rounded mt-2">
                                <i class="fas fa-undo mr-1"></i> Hủy thay đổi
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-image text-muted mr-1"></i> Hình đại diện</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="form-group mb-0">
                            <div class="input-group mb-3">
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input" id="customFile" name="images">
                                    <label class="custom-file-label" for="customFile">Chọn tệp...</label>
                                </div>
                            </div>
                            @if($errors->has('images'))
                                <span class="text-danger small mb-2 d-block text-left"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('images') }}</span>
                            @endif
                            
                            <div class="mt-3 p-2 border rounded bg-light" style="min-height: 160px; display: flex; align-items: center; justify-content: center;">
                                @if(isset($hotel) && !empty($hotel->h_image))
                                    <img src="{{ asset(pare_url_file($hotel->h_image)) }}" alt="Image" class="img-fluid rounded shadow-sm" id="image_render" style="max-height: 150px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No Image" class="img-fluid rounded opacity-50" id="image_render" style="max-height: 150px; object-fit: cover;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-images text-muted mr-1"></i> Album ảnh</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted d-block" style="font-size: 14px;">Thêm ảnh vào album</label>
                            <div class="custom-file">
                                <input type="file" name="album_images[]" id="album_images_hotel" multiple accept="image/*" class="custom-file-input">
                                <label class="custom-file-label" for="album_images_hotel">Chọn các tệp ảnh...</label>
                            </div>
                        </div>

                        {{-- Hiển thị ảnh album hiện có --}}
                        @php
                            $hotelAlbumImages = $hotel->h_anbum_image ?? [];
                            if (is_string($hotelAlbumImages)) {
                                $hotelAlbumImages = json_decode($hotelAlbumImages, true) ?: [];
                            }
                        @endphp
                        @if(isset($hotel) && !empty($hotelAlbumImages))
                            <label class="font-weight-bold text-muted" style="font-size: 14px;">Ảnh album hiện có:</label>
                            <div class="album-preview-grid mt-2" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap:10px;">
                                @foreach($hotelAlbumImages as $index => $albumImg)
                                    <div class="album-img-item" style="position:relative; width:100%; aspect-ratio: 1;">
                                        <img src="{{ asset(pare_url_file($albumImg)) }}" alt="" class="rounded shadow-sm w-100 h-100" style="object-fit:cover; border:1px solid #ddd;">
                                        <a href="{{ route('admin.hotel.remove-album-image', ['id' => $hotel->id, 'index' => $index]) }}"
                                           class="btn btn-sm btn-danger btn-confirm-delete d-flex align-items-center justify-content-center p-0"
                                           style="position:absolute; top:-5px; right:-5px; border-radius:50%; width:20px; height:20px; text-decoration:none; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                            <i class="fas fa-times" style="font-size: 10px;"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Preview ảnh mới chọn --}}
                        <div id="new-hotel-album-preview" class="mt-3" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('album_images_hotel').addEventListener('change', function(e) {
            var preview = document.getElementById('new-hotel-album-preview');
            preview.innerHTML = '';
            var files = this.files;
            
            // Update label
            var fileName = files.length > 1 ? files.length + " tệp được chọn" : files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
            
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = (function(file) {
                    return function(e) {
                        var div = document.createElement('div');
                        div.style.cssText = 'position:relative; width:100%; aspect-ratio: 1;';
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded shadow-sm w-100 h-100';
                        img.style.cssText = 'object-fit:cover; border:2px solid #007bff;';
                        div.appendChild(img);
                        preview.appendChild(div);
                    };
                })(files[i]);
                reader.readAsDataURL(files[i]);
            }
        });
        
        // Update main image input label
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('customFile');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    if(e.target.files.length > 0) {
                        var fileName = e.target.files[0].name;
                        var nextSibling = e.target.nextElementSibling;
                        nextSibling.innerText = fileName;
                    }
                });
            }
        });
    </script>
</div>
