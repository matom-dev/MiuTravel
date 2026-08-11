<style>
    .tour-form-sidebar {
        position: sticky;
        top: 73px;
        align-self: flex-start;
        max-height: calc(100vh - 89px);
        overflow-y: auto;
        scrollbar-width: thin;
    }

    @media (max-width: 767.98px) {
        .tour-form-sidebar {
            position: static;
            max-height: none;
            overflow: visible;
        }
    }

    .tour-program-card > .card-header {
        padding: 12px 16px 0;
    }

    .tour-program-card > .card-header .card-title {
        margin-bottom: 10px !important;
        font-size: 16px;
    }

    .tour-program-card > .card-body {
        padding: 16px;
    }

    .tour-program-card > .card-body > .form-group,
    .tour-program-card > .card-body > .row,
    .tour-program-card > .card-body > .card {
        margin-bottom: 14px !important;
    }

    .tour-program-card .control-label,
    .tour-program-card .activity-row label {
        margin-bottom: 5px;
        font-size: 13px;
    }

    .tour-program-card input.form-control,
    .tour-program-card select.form-control {
        height: 44px;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }

    .tour-program-card > .card-body > .card > .card-header {
        padding: 10px 12px;
    }

    .tour-program-card > .card-body > .card > .card-header h5 {
        font-size: 14px;
    }

    .tour-program-card > .card-body > .card > .card-body {
        padding: 12px;
    }

    .tour-program-card .activity-row {
        margin-bottom: 10px !important;
        padding: 12px !important;
    }

    .tour-program-card .staff-picker-layout {
        gap: 12px;
    }

    .tour-program-card .staff-picker-panel {
        padding: 10px;
    }

    .tour-program-card .staff-picker-heading {
        margin-bottom: 8px;
    }

    .tour-program-card .staff-picker-list {
        gap: 8px;
        max-height: 280px;
    }

    .tour-program-card .staff-picker-card {
        min-height: 66px;
        padding: 9px 38px 9px 9px;
    }

    .tour-program-card .staff-picker-avatar {
        width: 42px;
        height: 42px;
        flex-basis: 42px;
    }

    @media (max-width: 767.98px) {
        .tour-program-card > .card-body {
            padding: 12px;
        }

    }
</style>
<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card tour-program-card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h4 class="card-title font-weight-bold text-primary mb-3">Thông tin chương trình tour</h4>
                    </div>
                    <div class="card-body">

                        <div class="form-group {{ $errors->first('t_title') ? 'has-error' : '' }} mb-4">
                            <label for="t_title" class="control-label font-weight-bold text-muted">Tên tour <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="text" class="form-control px-3 py-2" id="t_title" placeholder="VD: Tour Động Thiên Đường - Suối Nước Moọc..." name="t_title" value="{{ old('t_title',isset($tour) ? $tour->t_title : '') }}" style="border-radius: 5px;">
                                @if($errors->has('t_title'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_title') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Địa điểm <sup class="text-danger">(*)</sup></label>
                                    <select class="form-control custom-select px-3 py-2" name="t_location_id" style="border-radius: 5px;">
                                        <option value="">-- Chọn địa điểm --</option>
                                        @foreach($locations as $location)
                                            <option {{old('t_location_id', isset($tour->t_location_id ) ? $tour->t_location_id  : '') == $location->id ? 'selected="selected"' : ''}} value="{{$location->id}}">
                                                {{$location->l_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('t_location_id'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_location_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Trạng thái</label>
                                    <select class="form-control custom-select px-3 py-2" name="t_status" style="border-radius: 5px;">
                                        @foreach($status as $key => $statu)
                                            <option {{old('t_status', isset($tour->t_status ) ? $tour->t_status : '') == $key ? 'selected="selected"' : ''}} value="{{$key}}">
                                                {{$statu}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('t_status'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_status') }}</span>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-12 col-md-4 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Giá người lớn (VNĐ) <sup class="text-danger">(*)</sup></label>
                                    <input type="number" class="form-control px-3 py-2" placeholder="Giá..." name="t_price_adults" value="{{ old('t_price_adults',isset($tour) ? $tour->t_price_adults : '') }}" style="border-radius: 5px;">
                                    @if($errors->has('t_price_adults'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_price_adults') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Giá trẻ em (VNĐ) <sup class="text-danger">(*)</sup></label>
                                    <input type="number" class="form-control px-3 py-2" placeholder="Giá..." name="t_price_children" value="{{ old('t_price_children',isset($tour) ? $tour->t_price_children : '') }}" style="border-radius: 5px;">
                                    @if($errors->has('t_price_children'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_price_children') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Khuyến mãi (%)</label>
                                    <input type="number" max="100" class="form-control px-3 py-2" placeholder="0" name="t_sale" value="{{ old('t_sale',isset($tour) ? $tour->t_sale : '') }}" style="border-radius: 5px;">
                                    @if($errors->has('t_sale'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_sale') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Phương tiện</label>
                                    <input type="text" class="form-control px-3 py-2" placeholder="VD: Ô tô, Máy bay..." name="t_move_method" value="{{ old('t_move_method',isset($tour) ? $tour->t_move_method : '') }}" style="border-radius: 5px;">
                                    @if($errors->has('t_move_method'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_move_method') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Điểm khởi hành</label>
                                    <input type="text" class="form-control px-3 py-2" placeholder="VD: Hà Nội..." name="t_starting_gate" value="{{ old('t_starting_gate',isset($tour) ? $tour->t_starting_gate : '') }}" style="border-radius: 5px;">
                                    @if($errors->has('t_starting_gate'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_starting_gate') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->first('t_journeys') ? 'has-error' : '' }} mb-4">
                            <label for="t_journeys" class="control-label font-weight-bold text-muted">Hành trình <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="text" class="form-control px-3 py-2" id="t_journeys" placeholder="VD: Hà Nội - Hạ Long..." name="t_journeys" value="{{ old('t_journeys',isset($tour) ? $tour->t_journeys : '') }}" style="border-radius: 5px;">
                                @if($errors->has('t_journeys'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_journeys') }}</span>
                                @endif
                            </div>
                        </div>

                        @php
                            $durationDaysValue = old('t_duration_days');
                            $durationNightsValue = old('t_duration_nights');

                            if ($durationDaysValue === null && isset($tour)) {
                                $durationDaysValue = $tour->effective_duration_days;
                            }

                            if ($durationNightsValue === null && isset($tour)) {
                                $durationNightsValue = $tour->effective_duration_nights;
                            }

                            $durationDaysValue = $durationDaysValue ?: 3;
                            $durationNightsValue = $durationNightsValue ?? 2;
                        @endphp

                        <div class="row mb-4">
                            <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                                <div class="form-group {{ $errors->first('t_duration_days') ? 'has-error' : '' }} mb-0">
                                    <label for="t_duration_days" class="control-label font-weight-bold text-muted">Số ngày <sup class="text-danger">(*)</sup></label>
                                    <input type="number" min="1" max="60" class="form-control px-3 py-2" id="t_duration_days" placeholder="VD: 3" name="t_duration_days" value="{{ $durationDaysValue }}" style="border-radius: 5px;">
                                    @if($errors->has('t_duration_days'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_duration_days') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group {{ $errors->first('t_duration_nights') ? 'has-error' : '' }} mb-0">
                                    <label for="t_duration_nights" class="control-label font-weight-bold text-muted">Số đêm <sup class="text-danger">(*)</sup></label>
                                    <input type="number" min="0" max="59" class="form-control px-3 py-2" id="t_duration_nights" placeholder="VD: 2" name="t_duration_nights" value="{{ $durationNightsValue }}" style="border-radius: 5px;">
                                    @if($errors->has('t_duration_nights'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_duration_nights') }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="form-group {{ $errors->first('t_content') ? 'has-error' : '' }} mb-4">
                            <label for="t_content" class="control-label font-weight-bold text-muted">Nội dung tour</label>
                            <div>
                                <textarea name="t_content" id="t_content" cols="30" rows="10" class="form-control" style="height: 225px;">{{ old('t_content', isset($tour) ? $tour->t_content : '') }}</textarea>
                                <script>
                                    ckeditorArticle(t_content);
                                </script>
                                @if ($errors->has('t_content'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_content') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->first('t_description') ? 'has-error' : '' }} mb-4">
                            <label for="t_description" class="control-label font-weight-bold text-muted">Lịch trình chi tiết</label>
                            <div>
                                <textarea name="t_description" id="t_description" cols="30" rows="10" class="form-control" style="height: 225px;">{{ old('t_description', isset($tour) ? $tour->t_description : '') }}</textarea>
                                <script>
                                    ckeditorArticle(t_description);
                                </script>
                                @if ($errors->has('t_description'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('t_description') }}</span>
                                @endif
                            </div>
                        </div>

                        @php
                            $oldActivityTitles = old('activity_title');
                            if (is_array($oldActivityTitles)) {
                                $tourActivities = [];
                                foreach ($oldActivityTitles as $idx => $title) {
                                    $tourActivities[] = [
                                        'title' => $title,
                                        'icon' => old('activity_icon')[$idx] ?? '',
                                        'description' => old('activity_description')[$idx] ?? '',
                                    ];
                                }
                            } else {
                                $tourActivities = isset($tour) ? ($tour->t_activities ?: []) : [];
                            }
                            if (empty($tourActivities)) {
                                $tourActivities = [['title' => '', 'icon' => 'fa fa-map-signs', 'description' => '']];
                            }

                            $selectedLeaderId = old('tour_leader_id');
                            $selectedGuideIds = old('tour_guide_ids');

                            if ($selectedLeaderId === null && isset($tour)) {
                                $selectedLeaderId = optional($tour->guideAssignments->firstWhere('tga_role', 'leader'))->tga_guide_id;
                            }

                            if ($selectedGuideIds === null && isset($tour)) {
                                $selectedGuideIds = $tour->guideAssignments
                                    ->where('tga_role', 'guide')
                                    ->pluck('tga_guide_id')
                                    ->map(function ($id) {
                                        return (string) $id;
                                    })
                                    ->toArray();
                            }

                            $selectedGuideIds = array_map('strval', (array) $selectedGuideIds);
                        @endphp

                        <div class="card border mb-4">
                            <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-hiking text-primary mr-1"></i> Hoạt động dịch vụ trong tour</h5>
                                <button type="button" class="btn btn-sm btn-primary ml-auto" id="add-activity-row">
                                    <i class="fas fa-plus mr-1"></i> Thêm hoạt động
                                </button>
                            </div>
                            <div class="card-body" id="activity-list">
                                @foreach($tourActivities as $activity)
                                    <div class="activity-row border rounded p-3 mb-3 bg-white">
                                        <div class="row">
                                            <div class="col-md-5 mb-3">
                                                <label class="font-weight-bold text-muted">Tên hoạt động</label>
                                                <input type="text" name="activity_title[]" class="form-control" placeholder="VD: Dã ngoại, Trekking..." value="{{ $activity['title'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="font-weight-bold text-muted">Icon FontAwesome</label>
                                                <input type="text" name="activity_icon[]" class="form-control" placeholder="fa fa-tree" value="{{ $activity['icon'] ?? 'fa fa-map-signs' }}">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger remove-activity-row">
                                                    <i class="fas fa-trash-alt mr-1"></i> Xóa
                                                </button>
                                            </div>
                                            <div class="col-12">
                                                <label class="font-weight-bold text-muted">Mô tả hoạt động</label>
                                                <textarea name="activity_description[]" rows="2" class="form-control" placeholder="Mô tả ngắn để du khách nắm rõ hoạt động...">{{ $activity['description'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <style>
                            .staff-picker-layout {
                                display: grid;
                                grid-template-columns: minmax(240px, .8fr) minmax(280px, 1.2fr);
                                gap: 18px;
                            }
                            .staff-picker-panel {
                                background: #f8fafc;
                                border: 1px solid #e5e7eb;
                                border-radius: 8px;
                                padding: 14px;
                            }
                            .staff-picker-heading {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                gap: 10px;
                                margin-bottom: 12px;
                            }
                            .staff-picker-heading strong {
                                color: #111827;
                                font-size: 14px;
                            }
                            .staff-picker-heading span {
                                color: #6b7280;
                                font-size: 12px;
                            }
                            .staff-picker-list {
                                display: grid;
                                gap: 10px;
                                max-height: 360px;
                                overflow-y: auto;
                                padding-right: 2px;
                            }
                            .staff-picker-option {
                                display: block;
                                margin: 0;
                                cursor: pointer;
                            }
                            .staff-picker-option input {
                                position: absolute;
                                opacity: 0;
                                pointer-events: none;
                            }
                            .staff-picker-card {
                                position: relative;
                                display: flex;
                                gap: 12px;
                                align-items: center;
                                min-height: 78px;
                                padding: 12px 42px 12px 12px;
                                background: #fff;
                                border: 1.5px solid #e5e7eb;
                                border-radius: 8px;
                                box-shadow: 0 8px 18px rgba(15,23,42,.04);
                                transition: border-color .2s, box-shadow .2s, transform .2s;
                            }
                            .staff-picker-option:hover .staff-picker-card {
                                border-color: #cbd5e1;
                                box-shadow: 0 12px 24px rgba(15,23,42,.08);
                                transform: translateY(-1px);
                            }
                            .staff-picker-option input:checked + .staff-picker-card {
                                border-color: #16a34a;
                                background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);
                                box-shadow: 0 12px 28px rgba(22,163,74,.14);
                            }
                            .staff-picker-avatar {
                                width: 48px;
                                height: 48px;
                                border-radius: 50%;
                                overflow: hidden;
                                flex: 0 0 48px;
                                background: #ecfdf5;
                                color: #16a34a;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-weight: 800;
                                border: 1px solid #d1fae5;
                            }
                            .staff-picker-avatar img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                                display: block;
                            }
                            .staff-picker-info {
                                min-width: 0;
                                flex: 1;
                            }
                            .staff-picker-name {
                                display: block;
                                color: #111827;
                                font-weight: 700;
                                font-size: 14px;
                                line-height: 1.25;
                                margin-bottom: 4px;
                            }
                            .staff-picker-meta {
                                display: flex;
                                flex-wrap: wrap;
                                gap: 6px;
                                color: #64748b;
                                font-size: 12px;
                                line-height: 1.35;
                            }
                            .staff-picker-check {
                                position: absolute;
                                right: 12px;
                                top: 50%;
                                transform: translateY(-50%);
                                width: 22px;
                                height: 22px;
                                border-radius: 50%;
                                border: 1.5px solid #cbd5e1;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: transparent;
                                background: #fff;
                                transition: all .2s;
                            }
                            .staff-picker-option input:checked + .staff-picker-card .staff-picker-check {
                                border-color: #16a34a;
                                background: #16a34a;
                                color: #fff;
                            }
                            .staff-picker-empty {
                                background: #fff;
                                border: 1px dashed #cbd5e1;
                                border-radius: 8px;
                                color: #64748b;
                                font-size: 13px;
                                padding: 14px;
                            }
                            @media (max-width: 991px) {
                                .staff-picker-layout {
                                    grid-template-columns: 1fr;
                                }
                            }
                        </style>

                        <div class="card border mb-4">
                            <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-user-tie text-success mr-1"></i> Hướng dẫn viên phụ trách</h5>
                                <a href="{{ route('tour.guide.create') }}" class="btn btn-sm btn-outline-success ml-auto">
                                    <i class="fas fa-plus mr-1"></i> Thêm nhân sự
                                </a>
                            </div>
                            <div class="card-body">
                                @if($tourLeaders->isEmpty() && $tourGuideStaff->isEmpty())
                                    <div class="alert alert-warning mb-0">
                                        Chưa có nhân sự tour đang hoạt động.
                                    </div>
                                @else
                                    <div class="staff-picker-layout">
                                        <div class="staff-picker-panel">
                                            <div class="staff-picker-heading">
                                                <strong><i class="fas fa-flag text-success mr-1"></i> Trưởng đoàn</strong>
                                                <span>{{ $tourLeaders->count() }} người</span>
                                            </div>
                                            <div class="staff-picker-list">
                                                <label class="staff-picker-option">
                                                    <input type="radio" name="tour_leader_id" value="" {{ empty($selectedLeaderId) ? 'checked' : '' }}>
                                                    <span class="staff-picker-card">
                                                        <span class="staff-picker-avatar"><i class="fas fa-user-slash"></i></span>
                                                        <span class="staff-picker-info">
                                                            <span class="staff-picker-name">Chưa chọn trưởng đoàn</span>
                                                        </span>
                                                        <span class="staff-picker-check"><i class="fas fa-check" style="font-size:11px;"></i></span>
                                                    </span>
                                                </label>
                                                @forelse($tourLeaders as $leader)
                                                    <label class="staff-picker-option">
                                                        <input type="radio" name="tour_leader_id" value="{{ $leader->id }}" {{ (string) $selectedLeaderId === (string) $leader->id ? 'checked' : '' }}>
                                                        <span class="staff-picker-card">
                                                            <span class="staff-picker-avatar">
                                                                @if($leader->tg_photo)
                                                                    <img src="{{ asset(pare_url_file($leader->tg_photo)) }}" alt="{{ $leader->tg_name }}">
                                                                @else
                                                                    {{ mb_substr($leader->tg_name, 0, 1) }}
                                                                @endif
                                                            </span>
                                                            <span class="staff-picker-info">
                                                                <span class="staff-picker-name">{{ $leader->tg_name }}</span>
                                                                <span class="staff-picker-meta">
                                                                    <span><i class="fas fa-user-tag mr-1"></i>{{ \App\Models\TourGuide::ROLES[$leader->tg_role] ?? 'Trưởng đoàn' }}</span>
                                                                    @if($leader->tg_phone)<span><i class="fas fa-phone-alt mr-1"></i>{{ $leader->tg_phone }}</span>@endif
                                                                    @if($leader->tg_hometown)<span><i class="fas fa-map-marker-alt mr-1"></i>{{ $leader->tg_hometown }}</span>@endif
                                                                </span>
                                                            </span>
                                                            <span class="staff-picker-check"><i class="fas fa-check" style="font-size:11px;"></i></span>
                                                        </span>
                                                    </label>
                                                @empty
                                                    <div class="staff-picker-empty">Chưa có trưởng đoàn đang hoạt động.</div>
                                                @endforelse
                                            </div>
                                            @if($errors->has('tour_leader_id'))
                                                <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tour_leader_id') }}</span>
                                            @endif
                                        </div>

                                        <div class="staff-picker-panel">
                                            <div class="staff-picker-heading">
                                                <strong><i class="fas fa-users text-success mr-1"></i> Hướng dẫn viên</strong>
                                            </div>
                                            <div class="staff-picker-list">
                                                @forelse($tourGuideStaff as $guideStaff)
                                                    <label class="staff-picker-option">
                                                        <input type="checkbox" name="tour_guide_ids[]" value="{{ $guideStaff->id }}" {{ in_array((string) $guideStaff->id, $selectedGuideIds, true) ? 'checked' : '' }}>
                                                        <span class="staff-picker-card">
                                                            <span class="staff-picker-avatar">
                                                                @if($guideStaff->tg_photo)
                                                                    <img src="{{ asset(pare_url_file($guideStaff->tg_photo)) }}" alt="{{ $guideStaff->tg_name }}">
                                                                @else
                                                                    {{ mb_substr($guideStaff->tg_name, 0, 1) }}
                                                                @endif
                                                            </span>
                                                            <span class="staff-picker-info">
                                                                <span class="staff-picker-name">{{ $guideStaff->tg_name }}</span>
                                                                <span class="staff-picker-meta">
                                                                    <span><i class="fas fa-user-tag mr-1"></i>{{ \App\Models\TourGuide::ROLES[$guideStaff->tg_role] ?? 'Hướng dẫn viên' }}</span>
                                                                    @if($guideStaff->tg_phone)<span><i class="fas fa-phone-alt mr-1"></i>{{ $guideStaff->tg_phone }}</span>@endif
                                                                    @if($guideStaff->tg_hometown)<span><i class="fas fa-map-marker-alt mr-1"></i>{{ $guideStaff->tg_hometown }}</span>@endif
                                                                </span>
                                                            </span>
                                                            <span class="staff-picker-check"><i class="fas fa-check" style="font-size:11px;"></i></span>
                                                        </span>
                                                    </label>
                                                @empty
                                                    <div class="staff-picker-empty">Chưa có hướng dẫn viên đang hoạt động.</div>
                                                @endforelse
                                            </div>
                                            @if($errors->has('tour_guide_ids.*'))
                                                <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tour_guide_ids.*') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 tour-form-sidebar admin-sticky-sidebar">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-cog text-muted mr-1"></i> Hành động</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2" style="gap: 10px;">
                            <button type="submit" name="submit" value="{{ isset($tour) ? 'update' : 'create' }}" class="btn btn-primary w-100 py-2 font-weight-bold rounded">
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
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-image text-muted mr-1"></i> Ảnh đại diện</h5>
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
                                @if(isset($tour) && !empty($tour->t_image))
                                    <img src="{{ asset(pare_url_file($tour->t_image)) }}" alt="Image" class="img-fluid rounded shadow-sm" id="image_render" style="max-height: 150px; object-fit: cover;">
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
                                <input type="file" name="album_images[]" id="album_images" multiple accept="image/*" class="custom-file-input">
                                <label class="custom-file-label" for="album_images">Chọn các tệp ảnh...</label>
                            </div>
                        </div>

                        {{-- Hiển thị ảnh album hiện có --}}
                        @php
                            $tourAlbumImages = $tour->t_anbum_image ?? [];
                            if (is_string($tourAlbumImages)) {
                                $tourAlbumImages = json_decode($tourAlbumImages, true) ?: [];
                            }
                        @endphp
                        @if(isset($tour) && !empty($tourAlbumImages))
                            <label class="font-weight-bold text-muted" style="font-size: 14px;">Ảnh album hiện có:</label>
                            <div class="album-preview-grid mt-2" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap:10px;">
                                @foreach($tourAlbumImages as $index => $albumImg)
                                    <div class="album-img-item" style="position:relative; width:100%; aspect-ratio: 1;">
                                        <img src="{{ asset(pare_url_file($albumImg)) }}" alt="" class="rounded shadow-sm w-100 h-100" style="object-fit:cover; border:1px solid #ddd;">
                                        <a href="{{ route('admin.tour.remove-album-image', ['id' => $tour->id, 'index' => $index]) }}"
                                           class="btn btn-sm btn-danger btn-confirm-delete d-flex align-items-center justify-content-center p-0"
                                           style="position:absolute; top:-5px; right:-5px; border-radius:50%; width:20px; height:20px; text-decoration:none; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                            <i class="fas fa-times" style="font-size: 10px;"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Preview ảnh mới chọn --}}
                        <div id="new-album-preview" class="mt-3" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function makeActivityRow() {
            var wrapper = document.createElement('div');
            wrapper.className = 'activity-row border rounded p-3 mb-3 bg-white';
            wrapper.innerHTML =
                '<div class="row">' +
                    '<div class="col-md-5 mb-3">' +
                        '<label class="font-weight-bold text-muted">Tên hoạt động</label>' +
                        '<input type="text" name="activity_title[]" class="form-control" placeholder="VD: Dã ngoại, Trekking...">' +
                    '</div>' +
                    '<div class="col-md-3 mb-3">' +
                        '<label class="font-weight-bold text-muted">Icon FontAwesome</label>' +
                        '<input type="text" name="activity_icon[]" class="form-control" placeholder="fa fa-tree" value="fa fa-map-signs">' +
                    '</div>' +
                    '<div class="col-md-4 mb-3 d-flex align-items-end">' +
                        '<button type="button" class="btn btn-outline-danger remove-activity-row"><i class="fas fa-trash-alt mr-1"></i> Xóa</button>' +
                    '</div>' +
                    '<div class="col-12">' +
                        '<label class="font-weight-bold text-muted">Mô tả hoạt động</label>' +
                        '<textarea name="activity_description[]" rows="2" class="form-control" placeholder="Mô tả ngắn để du khách nắm rõ hoạt động..."></textarea>' +
                    '</div>' +
                '</div>';
            return wrapper;
        }

        function makeGuideRow() {
            var wrapper = document.createElement('div');
            wrapper.className = 'guide-row border rounded p-3 mb-3 bg-white';
            wrapper.innerHTML =
                '<input type="hidden" name="guide_photo_old[]" value="">' +
                '<div class="row">' +
                    '<div class="col-md-4 mb-3">' +
                        '<label class="font-weight-bold text-muted">Họ tên</label>' +
                        '<input type="text" name="guide_name[]" class="form-control" placeholder="VD: Nguyễn Minh Anh">' +
                    '</div>' +
                    '<div class="col-md-4 mb-3">' +
                        '<label class="font-weight-bold text-muted">Vai trò</label>' +
                        '<input type="text" name="guide_role[]" class="form-control" placeholder="Trưởng đoàn / HDV địa phương" value="Hướng dẫn viên">' +
                    '</div>' +
                    '<div class="col-md-4 mb-3 d-flex align-items-end">' +
                        '<button type="button" class="btn btn-outline-danger remove-guide-row"><i class="fas fa-trash-alt mr-1"></i> Xóa</button>' +
                    '</div>' +
                    '<div class="col-md-4 mb-3">' +
                        '<label class="font-weight-bold text-muted">Ảnh hướng dẫn viên</label>' +
                        '<div class="d-flex align-items-center" style="gap:10px;">' +
                            '<div class="guide-photo-preview rounded-circle overflow-hidden bg-light border d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex:0 0 54px;">' +
                                '<i class="fas fa-user text-muted"></i>' +
                            '</div>' +
                            '<div class="custom-file" style="min-width:0;">' +
                                '<input type="file" name="guide_photo[]" accept="image/*" class="custom-file-input guide-photo-input">' +
                                '<label class="custom-file-label">Chọn ảnh...</label>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-4 mb-3">' +
                        '<label class="font-weight-bold text-muted">Số điện thoại</label>' +
                        '<input type="text" name="guide_phone[]" class="form-control" placeholder="VD: 0901 234 567">' +
                    '</div>' +
                    '<div class="col-md-4 mb-3">' +
                        '<label class="font-weight-bold text-muted">Email</label>' +
                        '<input type="email" name="guide_email[]" class="form-control" placeholder="guide@miutravel.vn">' +
                    '</div>' +
                    '<div class="col-md-6 mb-3 mb-md-0">' +
                        '<label class="font-weight-bold text-muted">Kinh nghiệm</label>' +
                        '<input type="text" name="guide_experience[]" class="form-control" placeholder="VD: 5 năm dẫn tour miền núi">' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<label class="font-weight-bold text-muted">Ngôn ngữ</label>' +
                        '<input type="text" name="guide_languages[]" class="form-control" placeholder="VD: Việt, Anh">' +
                    '</div>' +
                '</div>';
            return wrapper;
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('#add-activity-row')) {
                document.getElementById('activity-list').appendChild(makeActivityRow());
            }

            if (e.target.closest('.remove-activity-row')) {
                var activityRows = document.querySelectorAll('.activity-row');
                if (activityRows.length > 1) {
                    e.target.closest('.activity-row').remove();
                }
            }

            if (e.target.closest('#add-guide-row')) {
                document.getElementById('guide-list').appendChild(makeGuideRow());
            }

            if (e.target.closest('.remove-guide-row')) {
                var guideRows = document.querySelectorAll('.guide-row');
                if (guideRows.length > 1) {
                    e.target.closest('.guide-row').remove();
                }
            }

        });

        document.addEventListener('change', function(e) {
            if (!e.target.classList.contains('guide-photo-input') || !e.target.files.length) {
                return;
            }

            var file = e.target.files[0];
            var label = e.target.nextElementSibling;
            var row = e.target.closest('.guide-row');
            var preview = row ? row.querySelector('.guide-photo-preview') : null;

            if (label) {
                label.innerText = file.name;
            }

            if (preview && file.type.indexOf('image/') === 0) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    preview.innerHTML = '<img src="' + event.target.result + '" alt="Ảnh hướng dẫn viên" style="width:100%;height:100%;object-fit:cover;">';
                };
                reader.readAsDataURL(file);
            }
        });

        // Preview ảnh album mới trước khi upload
        document.getElementById('album_images').addEventListener('change', function(e) {
            var preview = document.getElementById('new-album-preview');
            preview.innerHTML = '';
            var files = this.files;
            if (!files.length) {
                return;
            }
            
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
