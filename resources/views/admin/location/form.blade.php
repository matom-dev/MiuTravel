<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h4 class="card-title font-weight-bold text-primary mb-3">Thông tin Địa điểm</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group {{ $errors->first('l_name') ? 'has-error' : '' }} mb-4">
                            <label for="l_name" class="control-label font-weight-bold text-muted">Tên địa điểm <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="text" maxlength="100" class="form-control px-3 py-2" id="l_name" placeholder="Nhập tên địa điểm..." name="l_name" value="{{ old('l_name',isset($location) ? $location->l_name : '') }}" style="border-radius: 5px;">
                                @if($errors->has('l_name'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('l_name') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group {{ $errors->first('l_description') ? 'has-error' : '' }} mb-4">
                            <label for="l_description" class="control-label font-weight-bold text-muted">Mô tả</label>
                            <div>
                                <textarea name="l_description" id="l_description" cols="20" rows="6" class="form-control p-3" placeholder="Nhập mô tả địa điểm..." style="border-radius: 5px; resize: vertical;">{{ old('l_description',isset($location) ? $location->l_description : '') }}</textarea>
                                @if($errors->has('l_description'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('l_description') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group {{ $errors->first('l_content') ? 'has-error' : '' }} mb-4">
                            <label for="l_content" class="control-label font-weight-bold text-muted">Giới thiệu chi tiết</label>
                            <div>
                                <textarea name="l_content" id="l_content" cols="30" rows="10" class="form-control" style="height: 225px;">{{ old('l_content', isset($location) ? $location->l_content : '') }}</textarea>
                                <script>
                                    ckeditorArticle(l_content);
                                </script>
                                @if ($errors->has('l_content'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('l_content') }}</span>
                                @endif
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
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-info-circle text-muted mr-1"></i> Trạng thái</h5>
                    </div>
                    <div class="card-body">
                        <select class="form-control custom-select px-3 py-2" name="l_status" style="border-radius: 5px;">
                            @foreach($status as $key => $item)
                                <option {{old('l_status', isset($location->l_status) ? $location->l_status : '') == $key ? 'selected="selected"' : ''}} value="{{$key}}">
                                    {{$item}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-image text-muted mr-1"></i> Hình ảnh</h5>
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
                                @if(isset($location) && !empty($location->l_image))
                                    <img src="{{ asset(pare_url_file($location->l_image)) }}" alt="Image" class="img-fluid rounded shadow-sm" id="image_render" style="max-height: 150px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No Image" class="img-fluid rounded opacity-50" id="image_render" style="max-height: 150px; object-fit: cover;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    // Custom file input label update
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('.custom-file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                var fileName = document.getElementById("customFile").files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        }
    });
</script>
