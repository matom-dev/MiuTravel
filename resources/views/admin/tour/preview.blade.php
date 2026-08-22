@extends('admin.layouts.main')
@section('title', 'Preview tour')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="font-weight-bold mb-1">Preview tour</h1>
                <div class="text-muted">{{ $tour->t_title }}</div>
            </div>
            @php
                $adminUser = Auth::guard('admins')->user();
                $canEditTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-tour']);
            @endphp
            @if($canEditTour)
            <a href="{{ route('tour.update', $tour->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit mr-1"></i> Chỉnh sửa</a>
            @endif
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    @if($tour->t_image)
                        <img src="{{ asset(pare_url_file($tour->t_image)) }}" alt="{{ $tour->t_title }}" style="height:320px;object-fit:cover;width:100%;">
                    @endif
                    <div class="card-body">
                        <span class="badge badge-light border mb-2">{{ optional($tour->location)->l_name ?: 'Chưa chọn địa điểm' }}</span>
                        <h2 class="h3 font-weight-bold">{{ $tour->t_title }}</h2>
                        <div class="text-muted mb-3">{{ $tour->t_journeys }} · {{ $tour->duration_text }} · {{ $tour->t_starting_gate }}</div>
                        <div class="mb-3">{!! $tour->t_description ?: '<p class="text-muted">Chưa có lịch trình chi tiết.</p>' !!}</div>
                        <div>{!! $tour->t_content ?: '<p class="text-muted">Chưa có nội dung tour.</p>' !!}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0"><h3 class="card-title font-weight-bold">Nhân sự tour</h3></div>
                    <div class="card-body">
                        @forelse($tour->guideAssignments as $assignment)
                            <div class="border-bottom py-2">
                                <strong>{{ optional($assignment->guide)->tg_name ?: 'Nhân sự không tồn tại' }}</strong>
                                <div class="small text-muted">{{ \App\Models\TourGuide::ASSIGNMENT_ROLES[$assignment->tga_role] ?? $assignment->tga_role }}</div>
                            </div>
                        @empty
                            <div class="text-muted">Chưa phân công hướng dẫn viên.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
