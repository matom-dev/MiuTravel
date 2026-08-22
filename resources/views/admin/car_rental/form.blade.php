<div class="container-fluid admin-form-page">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h4 class="card-title font-weight-bold text-primary mb-3">Thông tin dịch vụ thuê xe</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group {{ $errors->first('cr_name') ? 'has-error' : '' }} mb-4">
                            <label for="cr_name" class="control-label font-weight-bold text-muted">Tên dịch vụ / tên xe <sup class="text-danger">(*)</sup></label>
                            <input type="text" maxlength="191" class="form-control px-3 py-2" id="cr_name" placeholder="VD: Thuê xe 7 chỗ đi Phong Nha..." name="cr_name" value="{{ old('cr_name', isset($carRental) ? $carRental->cr_name : '') }}" style="border-radius:5px;">
                            @if($errors->has('cr_name'))
                                <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('cr_name') }}</span>
                            @endif
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="control-label font-weight-bold text-muted">Địa điểm</label>
                                <select class="form-control custom-select px-3 py-2" name="cr_location_id" style="border-radius:5px;">
                                    <option value="">-- Chọn địa điểm --</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('cr_location_id', isset($carRental) ? $carRental->cr_location_id : '') == $location->id ? 'selected' : '' }}>
                                            {{ $location->l_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label font-weight-bold text-muted">Trạng thái</label>
                                <select class="form-control custom-select px-3 py-2" name="cr_status" style="border-radius:5px;">
                                    @foreach($status as $key => $statu)
                                        <option value="{{ $key }}" {{ old('cr_status', isset($carRental) ? $carRental->cr_status : 1) == $key ? 'selected' : '' }}>
                                            {{ $statu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="control-label font-weight-bold text-muted">Loại xe</label>
                                @php $vehicleType = old('cr_vehicle_type', isset($carRental) ? strtolower((string) $carRental->cr_vehicle_type) : ''); @endphp
                                <select name="cr_vehicle_type" class="form-control custom-select px-3 py-2" style="border-radius:5px;">
                                    <option value="">Đang cập nhật</option>
                                    @foreach(\App\Models\CarRental::VEHICLE_TYPES as $key => $label)
                                        <option value="{{ $key }}" {{ $vehicleType === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="control-label font-weight-bold text-muted">Số chỗ</label>
                                <input type="number" min="1" class="form-control px-3 py-2" name="cr_number_seats" placeholder="4, 7, 16..." value="{{ old('cr_number_seats', isset($carRental) ? $carRental->cr_number_seats : '') }}" style="border-radius:5px;">
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="control-label font-weight-bold text-muted">Hình thức thuê</label>
                                @php $driverOption = old('cr_driver_option', isset($carRental) ? $carRental->cr_driver_option : ''); @endphp
                                <select name="cr_driver_option" class="form-control custom-select px-3 py-2" style="border-radius:5px;">
                                    <option value="">Linh hoạt</option>
                                    @foreach(\App\Models\CarRental::DRIVER_OPTIONS as $key => $label)
                                        <option value="{{ $key }}" {{ $driverOption === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="control-label font-weight-bold text-muted">Hộp số</label>
                                <select name="cr_transmission" class="form-control custom-select px-3 py-2" style="border-radius:5px;">
                                    @php $transmission = old('cr_transmission', isset($carRental) ? $carRental->cr_transmission : ''); @endphp
                                    <option value="">Đang cập nhật</option>
                                    <option value="Tự động" {{ $transmission == 'Tự động' ? 'selected' : '' }}>Tự động</option>
                                    <option value="Số sàn" {{ $transmission == 'Số sàn' ? 'selected' : '' }}>Số sàn</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label font-weight-bold text-muted">Nhiên liệu</label>
                                <input type="text" class="form-control px-3 py-2" name="cr_fuel" placeholder="Xăng, dầu, điện..." value="{{ old('cr_fuel', isset($carRental) ? $carRental->cr_fuel : '') }}" style="border-radius:5px;">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="control-label font-weight-bold text-muted">Điện thoại đơn vị cho thuê <sup class="text-danger">(*)</sup></label>
                                <input type="text" class="form-control px-3 py-2" name="cr_phone" placeholder="Nhập số điện thoại khách sẽ gọi trực tiếp..." value="{{ old('cr_phone', isset($carRental) ? $carRental->cr_phone : '') }}" style="border-radius:5px;">
                                @if($errors->has('cr_phone'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('cr_phone') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="control-label font-weight-bold text-muted">Điểm nhận xe / địa chỉ</label>
                                <input type="text" class="form-control px-3 py-2" name="cr_address" placeholder="Nhập địa chỉ..." value="{{ old('cr_address', isset($carRental) ? $carRental->cr_address : '') }}" style="border-radius:5px;">
                            </div>
                        </div>

                        <div class="form-group {{ $errors->first('cr_content') ? 'has-error' : '' }} mb-4">
                            <label for="cr_content" class="control-label font-weight-bold text-muted">Nội dung dịch vụ thuê xe <sup class="text-danger">(*)</sup></label>
                            <textarea name="cr_content" id="cr_content" rows="10" class="form-control" style="resize:vertical;height:420px;" placeholder="Giới thiệu loại xe, phạm vi phục vụ, thủ tục và chính sách thuê xe...">{{ old('cr_content', isset($carRental) ? ($carRental->cr_content ?: $carRental->cr_description) : '') }}</textarea>
                            @if($errors->has('cr_content'))
                                <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('cr_content') }}</span>
                            @endif
                            <script>
                                ckeditorArticle(cr_content);
                            </script>
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
                        <button type="submit" name="submit" class="btn btn-primary w-100 py-2 font-weight-bold rounded">
                            <i class="fas fa-save mr-1"></i> Lưu dữ liệu
                        </button>
                        <button type="reset" class="btn btn-outline-secondary w-100 py-2 rounded mt-2">
                            <i class="fas fa-undo mr-1"></i> Hủy thay đổi
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-image text-muted mr-1"></i> Hình đại diện</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="custom-file text-left mb-3">
                            <input type="file" class="custom-file-input" id="customFile" name="images">
                            <label class="custom-file-label" for="customFile">Chọn tệp...</label>
                        </div>
                        @if($errors->has('images'))
                            <span class="text-danger small mb-2 d-block text-left"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('images') }}</span>
                        @endif
                        <div class="mt-3 p-2 border rounded bg-light" style="min-height:160px;display:flex;align-items:center;justify-content:center;">
                            @if(isset($carRental) && $carRental->cr_image)
                                <img src="{{ asset(pare_url_file($carRental->cr_image)) }}" alt="Image" class="img-fluid rounded shadow-sm" id="image_render" style="max-height:150px;object-fit:cover;">
                            @else
                                <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No Image" class="img-fluid rounded opacity-50" id="image_render" style="max-height:150px;object-fit:cover;">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-images text-muted mr-1"></i> Album ảnh</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted d-block" style="font-size:14px;">Thêm ảnh album</label>
                            <div class="custom-file">
                                <input type="file" name="album_images[]" id="album_images_car" multiple accept="image/*" class="custom-file-input">
                                <label class="custom-file-label" for="album_images_car">Chọn các tệp ảnh...</label>
                            </div>
                        </div>

                        @php $carAlbumImages = $carRental->cr_album_images ?? []; @endphp
                        @if(isset($carRental) && !empty($carAlbumImages))
                            <label class="font-weight-bold text-muted" style="font-size:14px;">Ảnh album hiện có:</label>
                            <div class="album-preview-grid mt-2" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(70px,1fr));gap:10px;">
                                @foreach($carAlbumImages as $index => $albumImg)
                                    <div class="album-img-item" style="position:relative;width:100%;aspect-ratio:1;">
                                        <img src="{{ asset(pare_url_file($albumImg)) }}" alt="" class="rounded shadow-sm w-100 h-100" style="object-fit:cover;border:1px solid #ddd;">
                                        <a href="{{ route('admin.car.rental.remove-album-image', ['id' => $carRental->id, 'index' => $index]) }}"
                                           class="btn btn-sm btn-danger btn-confirm-delete d-flex align-items-center justify-content-center p-0"
                                           style="position:absolute;top:-5px;right:-5px;border-radius:50%;width:20px;height:20px;text-decoration:none;box-shadow:0 2px 4px rgba(0,0,0,.2);">
                                            <i class="fas fa-times" style="font-size:10px;"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div id="new-car-album-preview" class="mt-3" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(70px,1fr));gap:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('album_images_car').addEventListener('change', function(e) {
            var preview = document.getElementById('new-car-album-preview');
            preview.innerHTML = '';
            var files = this.files;
            if (!files.length) return;

            e.target.nextElementSibling.innerText = files.length > 1 ? files.length + ' tệp được chọn' : files[0].name;

            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var div = document.createElement('div');
                    div.style.cssText = 'position:relative;width:100%;aspect-ratio:1;';
                    var img = document.createElement('img');
                    img.src = event.target.result;
                    img.className = 'rounded shadow-sm w-100 h-100';
                    img.style.cssText = 'object-fit:cover;border:2px solid #007bff;';
                    div.appendChild(img);
                    preview.appendChild(div);
                };
                reader.readAsDataURL(files[i]);
            }
        });
    </script>
</div>
