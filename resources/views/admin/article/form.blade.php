<style>
    .article-form-shell {
        --article-border: #e8edf4;
        --article-muted: #718096;
        --article-soft: #f7f9fc;
    }

    .article-section-card {
        border: 1px solid var(--article-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .article-section-card .card-header {
        background: #fff;
        border-bottom: 1px solid var(--article-border);
        padding: 18px 22px;
    }

    .article-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #1f2937;
        font-size: 17px;
        font-weight: 800;
    }

    .article-section-title span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #fff1ec;
        color: #f15d30;
        font-size: 14px;
        font-weight: 900;
    }

    .article-side-card {
        border: 1px solid var(--article-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .article-help-list {
        padding-left: 18px;
        margin-bottom: 0;
        color: var(--article-muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .article-image-preview {
        min-height: 170px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px dashed #d6dde8;
        border-radius: 10px;
        background: var(--article-soft);
    }
</style>

<div class="container-fluid admin-form-page article-form-shell">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm article-section-card mb-4" id="article-basic">
                    <div class="card-header">
                        <h4 class="article-section-title"><span>1</span> Thông tin chính</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group {{ $errors->first('a_title') ? 'has-error' : '' }} mb-4">
                            <label for="a_title" class="control-label font-weight-bold text-muted">Tiêu đề bài viết <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="text" maxlength="180" class="form-control px-3 py-2" id="a_title" placeholder="Nhập tiêu đề bài viết..." name="a_title" value="{{ old('a_title',isset($article) ? $article->a_title : '') }}" style="border-radius: 5px;">
                                @if($errors->has('a_title'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('a_title') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Danh mục <sup class="text-danger">(*)</sup></label>
                                    <select class="form-control custom-select px-3 py-2" name="a_category_id" style="border-radius: 5px;">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $category)
                                            <option {{old('a_category_id', isset($article->a_category_id ) ? $article->a_category_id  : '') == $category->id ? 'selected="selected"' : ''}} value="{{$category->id}}">
                                                {{$category->c_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('a_category_id'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('a_category_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Trạng thái</label>
                                    <select class="form-control custom-select px-3 py-2" name="a_active" style="border-radius: 5px;">
                                        @foreach($actives as $key => $active)
                                            <option {{old('a_active', isset($article->a_active ) ? $article->a_active : '') == $key ? 'selected="selected"' : ''}} value="{{$key}}">
                                                {{$active}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('a_active'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('a_active') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm article-section-card mb-4" id="article-content">
                    <div class="card-header">
                        <h4 class="article-section-title"><span>2</span> Nội dung bài viết</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group {{ $errors->first('a_content') ? 'has-error' : '' }} mb-0">
                            <label for="a_content" class="control-label font-weight-bold text-muted">Nội dung bài viết <sup class="text-danger">(*)</sup></label>
                            <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 8px;">
                                <button type="button" class="btn btn-primary btn-sm" id="insert-inline-article-image">
                                    <i class="fas fa-image mr-1"></i> Chèn ảnh tại đây
                                </button>
                                <input type="file" id="inline_article_image_input" accept="image/*" style="display:none;">
                            </div>
                            <div>
                                <textarea name="a_content" id="a_content" cols="30" rows="10" class="form-control" style="height: 420px;">{{ old('a_content', isset($article) ? ($article->a_content ?: $article->a_description) : '') }}</textarea>
                                <script>
                                    ckeditorArticle(a_content);
                                </script>
                                @if ($errors->has('a_content'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('a_content') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm article-section-card mb-4" id="article-media">
                    <div class="card-header">
                        <h4 class="article-section-title"><span>3</span> Album ảnh phụ</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="control-label font-weight-bold text-muted">Chọn nhiều ảnh</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input js-album-file-input" id="articleAlbumImages" name="album_images[]" accept="image/*" multiple>
                                <label class="custom-file-label" for="articleAlbumImages">Chọn nhiều tệp...</label>
                            </div>
                            @if($errors->has('album_images.*'))
                                <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('album_images.*') }}</span>
                            @endif
                            @if(isset($article) && !empty($article->a_album_images))
                                <div class="row mt-3">
                                    @foreach($article->a_album_images as $albumImage)
                                        <div class="col-6 col-md-3 mb-3">
                                            <img src="{{ asset(pare_url_file($albumImage)) }}" alt="Album ảnh" class="img-fluid rounded shadow-sm" style="height: 110px; width: 100%; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 admin-sticky-sidebar">
                <div class="card shadow-sm article-side-card mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-list-ul text-primary mr-1"></i> Các mục nhập</h5>
                    </div>
                    <div class="card-body">
                        <ol class="article-help-list">
                            <li>Thông tin chính</li>
                            <li>Nội dung bài viết</li>
                            <li>Album ảnh phụ</li>
                            <li>Ảnh đại diện</li>
                        </ol>
                    </div>
                </div>

                <div class="card shadow-sm article-side-card mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-cog text-muted mr-1"></i> Hành động</h5>
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
                
                <div class="card shadow-sm article-side-card mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-image text-muted mr-1"></i> Ảnh đại diện</h5>
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
                            
                            <div class="article-image-preview mt-3 p-2">
                                @if(isset($article) && !empty($article->a_avatar))
                                    <img src="{{ asset(pare_url_file($article->a_avatar)) }}" alt="Avatar" class="img-fluid rounded shadow-sm" id="image_render" style="max-height: 150px; object-fit: cover;">
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
        document.querySelectorAll('.custom-file-input').forEach(function(fileInput) {
            fileInput.addEventListener('change', function(e) {
                var files = e.target.files || [];
                if (!files.length) return;

                var fileName = files.length > 1 ? files.length + ' ảnh đã chọn' : files[0].name;
                var nextSibling = e.target.nextElementSibling;
                if (nextSibling) nextSibling.innerText = fileName;
            });
        });

        var insertInlineArticleImage = document.getElementById('insert-inline-article-image');
        var inlineArticleImageInput = document.getElementById('inline_article_image_input');
        var savedArticleContentRanges = null;

        function notifyInlineUpload(message, type) {
            if (window.toastr && typeof toastr[type || 'error'] === 'function') {
                toastr[type || 'error'](message);
                return;
            }

            alert(message);
        }

        function insertArticleInlineImage(url) {
            var editor = CKEDITOR.instances.a_content;
            if (!editor) return;

            var safeUrl = String(url || '').replace(/"/g, '&quot;');
            if (!safeUrl) return;

            editor.focus();
            if (savedArticleContentRanges && savedArticleContentRanges.length) {
                editor.getSelection().selectRanges(savedArticleContentRanges);
            }

            editor.insertHtml([
                '<p><img class="news-inline-image" src="' + safeUrl + '" alt="Hình ảnh bài viết"></p>',
                '<p class="news-image-caption">Viết chú thích ảnh tại đây.</p>',
                '<p>Tiếp tục viết nội dung ở đây...</p>'
            ].join(''));
        }

        if (insertInlineArticleImage) {
            insertInlineArticleImage.addEventListener('click', function() {
                var editor = CKEDITOR.instances.a_content;
                if (!editor || !inlineArticleImageInput) return;

                editor.focus();
                var selection = editor.getSelection();
                savedArticleContentRanges = selection ? selection.getRanges() : null;

                inlineArticleImageInput.value = '';
                inlineArticleImageInput.click();
            });
        }

        if (inlineArticleImageInput) {
            inlineArticleImageInput.addEventListener('change', function(e) {
                var file = e.target.files && e.target.files[0] ? e.target.files[0] : null;
                if (!file) return;

                if (!file.type.match(/^image\//)) {
                    notifyInlineUpload('Vui lòng chọn đúng file ảnh.');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    notifyInlineUpload('Ảnh tối đa 5MB. Vui lòng chọn ảnh nhỏ hơn.');
                    return;
                }

                var formData = new FormData();
                formData.append('image', file);

                insertInlineArticleImage.disabled = true;
                insertInlineArticleImage.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang tải ảnh...';

                fetch('{{ route('admin.article.upload-inline-image') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                }).then(function(response) {
                    return response.json().then(function(data) {
                        if (!response.ok) {
                            throw new Error(data.message || 'Không tải được ảnh.');
                        }

                        return data;
                    });
                }).then(function(data) {
                    insertArticleInlineImage(data.url);
                    notifyInlineUpload('Đã chèn ảnh vào bài viết.', 'success');
                }).catch(function(error) {
                    notifyInlineUpload(error.message || 'Không tải được ảnh. Vui lòng thử lại.');
                }).finally(function() {
                    insertInlineArticleImage.disabled = false;
                    insertInlineArticleImage.innerHTML = '<i class="fas fa-image mr-1"></i> Chèn ảnh tại đây';
                });
            });
        }
    });
</script>
