@extends('admin.layouts.main')
@section('title', 'Preview bài viết')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="font-weight-bold mb-1">Preview bài viết</h1>
                <div class="text-muted">{{ $article->a_title }}</div>
            </div>
            @php
                $adminUser = Auth::guard('admins')->user();
                $canEditArticle = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-noi-dung']);
            @endphp
            @if($canEditArticle)
            <a href="{{ route('article.update', $article->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit mr-1"></i> Chỉnh sửa</a>
            @endif
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    @if($article->a_avatar)
                        <img src="{{ asset(pare_url_file($article->a_avatar)) }}" alt="{{ $article->a_title }}" style="height:320px;object-fit:cover;width:100%;">
                    @endif
                    <div class="card-body">
                        <span class="badge badge-light border mb-2">{{ optional($article->category)->c_name ?: 'Chưa chọn danh mục' }}</span>
                        <h2 class="h3 font-weight-bold">{{ $article->a_title }}</h2>
                        @if($article->a_description)
                            <p class="text-muted">{{ $article->a_description }}</p>
                        @endif
                        <div>{!! $article->a_content ?: '<p class="text-muted">Bài viết chưa có nội dung.</p>' !!}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">Thông tin xuất bản</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-2"><strong>Slug:</strong> <span class="text-muted">{{ $article->a_slug ?: '---' }}</span></div>
                        <div class="mb-2"><strong>Trạng thái:</strong> {{ \App\Models\Article::ACTIVES[$article->a_active] ?? 'Không rõ' }}</div>
                        <div><strong>Ngày tạo:</strong> {{ $article->created_at ? $article->created_at->format('d/m/Y H:i') : '---' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
