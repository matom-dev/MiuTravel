@extends('admin.layouts.main')
@section('title', 'Quản lý Bài viết')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Quản lý Bài viết</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Bài viết</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Form Tìm kiếm -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-0 bg-white pb-0">
                    <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-search mr-1"></i> Tìm kiếm</h3>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="admin-search-form">
                        <div class="row align-items-end">
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Tiêu đề bài viết</label>
                                <input type="text" name="a_title" value="{{ Request::get('a_title') }}" class="form-control" placeholder="Nhập tiêu đề...">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Danh mục</label>
                                <select class="form-control custom-select" name="a_category_id">
                                    <option value="">-- Tất cả danh mục --</option>
                                    @foreach($categories as $category)
                                        @if (isset($category->children) && count($category->children) > 0)
                                            <optgroup label="{{ $category->c_name }}">
                                                @foreach($category->children as $children)
                                                    <option value="{{$children->id}}" {{ Request::get('a_category_id') == $children->id ? 'selected' : '' }}>
                                                        {{$children->c_name}}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{$category->id}}" {{ Request::get('a_category_id') == $category->id ? 'selected' : '' }}>
                                                {{$category->c_name}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Trạng thái</label>
                                <select name="a_active" class="form-control custom-select">
                                    <option value="">Tất cả</option>
                                    @foreach($actives as $key => $item)
                                        <option value="{{ $key }}" {{ (string) Request::get('a_active') === (string) $key ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Sắp xếp</label>
                                <select name="sort" class="form-control custom-select">
                                    <option value="latest" {{ Request::get('sort', 'latest') === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="oldest" {{ Request::get('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                    <option value="title_asc" {{ Request::get('sort') === 'title_asc' ? 'selected' : '' }}>Tiêu đề A-Z</option>
                                    <option value="title_desc" {{ Request::get('sort') === 'title_desc' ? 'selected' : '' }}>Tiêu đề Z-A</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2 admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                <a href="{{ route('article.index') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Làm mới</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bảng danh sách -->
            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Danh sách Bài viết</h3>
                    <div class="card-tools ml-auto">
                        <a href="{{ route('article.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th>Hình Ảnh</th>
                                <th width="35%">Tiêu Đề</th>
                                <th>Danh Mục</th>
                                <th class="text-center">Trạng Thái</th>
                                <th class="text-center">Ngày Đăng</th>
                                <th width="12%" class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$articles->isEmpty())
                                @php $i = $articles->firstItem(); @endphp
                                @foreach($articles as $article)
                                    <tr>
                                        <td class="text-center text-muted">{{ $i }}</td>
                                        <td>
                                            <div class="p-1 border rounded bg-light d-flex align-items-center justify-content-center" style="width: 130px; height: 90px; overflow: hidden;">
                                                @if(isset($article) && !empty($article->a_avatar))
                                                    <img src="{{ asset(pare_url_file($article->a_avatar)) }}" alt="{{ $article->a_title }}" class="img-fluid rounded" style="max-width: 100%; max-height: 100%; object-fit: cover; width: 100%; height: 100%;">
                                                @else
                                                    <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No image" class="img-fluid rounded opacity-50" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <p class="font-weight-medium mb-0 text-truncate" style="max-width: 300px;">{{ $article->a_title }}</p>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ isset($article->category) ? $article->category->c_name : '---' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($article->a_active == 1)
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Hiển thị</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-eye-slash mr-1"></i> Ẩn</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted" style="font-size: 13px;">{{ date('d-m-Y', strtotime($article->created_at)) }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('article.update', $article->id) }}" class="btn btn-info btn-sm" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('article.delete', $article->id) }}" class="btn btn-danger btn-sm btn-confirm-delete" title="Xóa">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-newspaper fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có bài viết nào được tìm thấy.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($articles->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="float-right">
                            {{ $articles->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
