@extends('admin.layouts.main')
@section('title', 'Quản lý Bình luận')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Quản lý Bình luận</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Bình luận</li>
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
                    <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-search mr-1"></i> Tìm kiếm Bình luận</h3>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="admin-search-form">
                        <div class="row align-items-end">
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Tên người bình luận</label>
                                <input type="text" name="name" value="{{ Request::get('name') }}" class="form-control" placeholder="Nhập tên người dùng...">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Email</label>
                                <input type="text" name="email" value="{{ Request::get('email') }}" class="form-control" placeholder="Nhập email...">
                            </div>
                            <div class="col-sm-12 col-md-2 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="">Tất cả</option>
                                    @foreach($status as $key => $label)
                                        <option value="{{ $key }}" {{ (string) Request::get('status') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Điểm sao</label>
                                <select name="rating" class="form-control">
                                    <option value="">Tất cả</option>
                                    @for($star = 5; $star >= 1; $star--)
                                        <option value="{{ $star }}" {{ (string) Request::get('rating') === (string) $star ? 'selected' : '' }}>{{ $star }} sao</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2 admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                <a href="{{ route('comment.index') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Xóa lọc</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách -->
            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Danh sách Bình luận</h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th width="25%">Người bình luận</th>
                                <th width="40%">Nội dung</th>
                                <th class="text-center">Sao</th>
                                <th class="text-center">Trạng Thái</th>
                                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly']))
                                    <th width="10%" class="text-center">Hành Động</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$comments->isEmpty())
                                @php $i = $comments->firstItem(); @endphp
                                @foreach($comments as $comment)
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $i }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3 text-center bg-light rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 40px; height: 40px; font-weight: bold; font-size: 16px;">
                                                    {{ mb_substr(isset($comment->user) ? $comment->user->name : 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-weight-bold mb-0">{{ isset($comment->user) ? $comment->user->name : '---' }}</p>
                                                    <small class="text-muted"><i class="fas fa-envelope mr-1"></i> {{ isset($comment->user) ? $comment->user->email : '---' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <p class="mb-1 text-break" style="font-size: 14.5px;">{{ $comment->cm_content }}</p>
                                            @php
                                                $cmLink = null;
                                                $cmLabel = null;
                                                $cmIcon  = null;
                                                if ($comment->cm_article_id && $comment->article) {
                                                    $cmLink  = article_url($comment->article) . '#comments';
                                                    $cmLabel = $comment->article->a_title;
                                                    $cmIcon  = 'fas fa-newspaper';
                                                } elseif ($comment->cm_tour_id && $comment->tour) {
                                                    $cmLink  = route('tour.detail', ['id' => $comment->tour->id, 'slug' => safeTitle($comment->tour->t_title)]) . '#comments';
                                                    $cmLabel = $comment->tour->t_title;
                                                    $cmIcon  = 'fas fa-map-marked-alt';
                                                } elseif ($comment->cm_hotel_id && $comment->hotel) {
                                                    $cmLink  = route('hotel.detail', ['id' => $comment->hotel->id, 'slug' => safeTitle($comment->hotel->h_name)]) . '#comments';
                                                    $cmLabel = $comment->hotel->h_name;
                                                    $cmIcon  = 'fas fa-hotel';
                                                }
                                            @endphp
                                            @if($cmLink)
                                                <a href="{{ $cmLink }}" target="_blank" class="d-inline-flex align-items-center mt-1 text-primary" style="font-size: 12.5px; text-decoration: none;">
                                                    <i class="{{ $cmIcon }} mr-1"></i>
                                                    <span class="text-truncate" style="max-width: 280px;">{{ $cmLabel }}</span>
                                                    <i class="fas fa-external-link-alt ml-1" style="font-size: 10px;"></i>
                                                </a>
                                            @endif
                                            @if($comment->cm_rating)
                                                <div class="mt-2 text-warning" style="font-size:13px;">
                                                    @for($star = 1; $star <= 5; $star++)
                                                        <i class="{{ $star <= $comment->cm_rating ? 'fas' : 'far' }} fa-star"></i>
                                                    @endfor
                                                </div>
                                            @endif
                                            @php $commentImages = is_array($comment->cm_images) ? $comment->cm_images : []; @endphp
                                            @if(!empty($commentImages))
                                                <div class="d-flex flex-wrap mt-2" style="gap:6px;">
                                                    @foreach($commentImages as $image)
                                                        <a href="{{ asset(pare_url_file($image)) }}" target="_blank" class="d-inline-block rounded overflow-hidden border" style="width:54px;height:54px;background:#f8f9fa;">
                                                            <img src="{{ asset(pare_url_file($image)) }}" alt="Ảnh check-in" style="width:100%;height:100%;object-fit:cover;">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ $comment->cm_rating ? $comment->cm_rating . '/5' : '--' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge {{ str_replace('btn-', 'badge-', $classStatus[$comment->cm_status] ?? 'badge-secondary') }} px-2 py-1" style="font-size: 12px;">
                                                {{ $status[$comment->cm_status] ?? 'Không rõ' }}
                                            </span>
                                        </td>
                                        @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly']))
                                            <td class="text-center align-middle">
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{$comment->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Thao tác
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" aria-labelledby="dropdownMenuButton{{$comment->id}}">
                                                        <h6 class="dropdown-header">Chuyển trạng thái</h6>
                                                        @foreach($status as $key => $item)
                                                            @if($comment->cm_status != $key)
                                                                <a class="dropdown-item update_book_tour py-2" style="cursor: pointer;" url="{{ route('comment.update.status', ['status' => $key, 'id' => $comment->id]) }}">
                                                                    <i class="far fa-circle text-muted mr-2"></i>{{ $item }}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger btn-confirm-delete py-2" href="{{ route('comment.delete', $comment->id) }}">
                                                            <i class="fas fa-trash-alt mr-2"></i> Xóa bình luận
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ Auth::guard('admins')->user()->can(['full-quyen-quan-ly']) ? 6 : 5 }}" class="text-center py-5 text-muted">
                                        <i class="fas fa-comments fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có bình luận nào.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($comments->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="float-right">
                            {{ $comments->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
