@if($comments->count() > 0)
    {{-- ══════════════════════════════════════════════
    SECTION: BÌNH LUẬN NỔI BẬT
    ══════════════════════════════════════════════ --}}
    <section class="rv-section">
        {{-- Nền gradient --}}
        <div class="rv-overlay"></div>

        <div class="container rv-container">

            {{-- Tiêu đề --}}
            <div class="rv-header">
                <div class="rv-heading-copy">
                    <h2 class="rv-title">Đánh giá của du khách</h2>
                    <p class="rv-desc">Những trải nghiệm đáng nhớ được khách hàng gửi lại sau chuyến đi.</p>
                </div>

                {{-- Thống kê nhanh --}}
                <div class="rv-stats">
                    <div class="rv-stat">
                        <span class="rv-stat__number">{{ $comments->count() }}+</span>
                        <span class="rv-stat__label">Đánh giá</span>
                    </div>
                    <div class="rv-stat-divider"></div>
                    <div class="rv-stat">
                        <span class="rv-stat__number">4.9</span>
                        <span class="rv-stat__label">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                            <i class="fa fa-star"></i><i class="fa fa-star"></i>
                        </span>
                    </div>
                    <div class="rv-stat-divider"></div>
                    <div class="rv-stat">
                        <span class="rv-stat__number">98%</span>
                        <span class="rv-stat__label">Hài lòng</span>
                    </div>
                </div>
            </div>

            {{-- Carousel --}}
            <div class="rv-carousel-wrap">
                <div class="rv-carousel owl-carousel" id="rv-owl">
                    @foreach($comments as $comment)
                        @php
                            $avatar = (!empty($comment->user->avatar))
                                ? asset(pare_url_file($comment->user->avatar))
                                : asset('page/images/person_1.jpg');
                            $name = $comment->user->name ?? 'Ẩn danh';
                            $initial = mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8');
                            $checkinImages = is_array($comment->cm_images) ? $comment->cm_images : [];
                            $visibleCheckins = array_slice($checkinImages, 0, 3);
                            $hiddenCheckins = max(count($checkinImages) - count($visibleCheckins), 0);

                            // Xác định link trang nguồn
                            $sourceUrl = null;
                            $sourceLabel = null;
                            if ($comment->cm_article_id && $comment->article) {
                                $sourceUrl = article_url($comment->article) . '#comments';
                                $sourceLabel = $comment->article->a_title;
                            } elseif ($comment->cm_tour_id && $comment->tour) {
                                $sourceUrl = route('tour.detail', ['id' => $comment->tour->id, 'slug' => safeTitle($comment->tour->t_title)]) . '#comments';
                                $sourceLabel = $comment->tour->t_title;
                            } elseif ($comment->cm_hotel_id && $comment->hotel) {
                                $sourceUrl = route('hotel.detail', ['id' => $comment->hotel->id, 'slug' => safeTitle($comment->hotel->h_name)]) . '#comments';
                                $sourceLabel = $comment->hotel->h_name;
                            }
                        @endphp
                        @php
                            $checkinUrls = array_map(function ($image) {
                                return asset(pare_url_file($image));
                            }, $checkinImages);
                        @endphp
                        <div class="rv-item rv-item--link" @if($sourceUrl) data-href="{{ $sourceUrl }}" @endif
                            style="display:block; text-decoration:none;">

                            <div class="rv-card-top">
                                <div class="rv-user">
                                    <div class="rv-avatar-wrap">
                                        <img src="{{ $avatar }}" alt="{{ $name }}" class="rv-avatar"
                                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                        <div class="rv-avatar-fallback" style="display:none;">{{ $initial }}</div>
                                    </div>
                                    <div class="rv-user-info">
                                        <span class="rv-user-name">{{ $name }}</span>
                                        <span class="rv-user-time">
                                            <i class="fa fa-clock-o"></i>
                                            {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                                <div class="rv-stars" aria-label="5 sao">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>

                            {{-- Nội dung bình luận --}}
                            <p class="rv-content">
                                {{ \Illuminate\Support\Str::limit(strip_tags($comment->cm_content), 180, '...') }}
                            </p>

                            <div class="rv-checkins rv-checkins--{{ count($visibleCheckins) ?: 'empty' }}">
                                @if(!empty($visibleCheckins))
                                    @foreach($visibleCheckins as $imageIndex => $image)
                                        <button type="button" class="rv-checkin-photo js-rv-gallery" data-images='@json($checkinUrls)'
                                            data-index="{{ $imageIndex }}" data-name="{{ $name }}">
                                            <img src="{{ asset(pare_url_file($image)) }}" alt="Ảnh check-in của {{ $name }}"
                                                loading="lazy">
                                            @if($hiddenCheckins > 0 && $imageIndex === count($visibleCheckins) - 1)
                                                <span class="rv-checkin-more">+{{ $hiddenCheckins }}</span>
                                            @endif
                                        </button>
                                    @endforeach
                                @else
                                    <div class="rv-checkin-empty">
                                        <i class="fa fa-camera"></i>
                                        <span>Chưa có ảnh check-in</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Nguồn bài viết --}}
                            @if($sourceLabel)
                                <div class="rv-source">
                                    <i class="fa fa-link"></i>
                                    <span>{{ \Illuminate\Support\Str::limit($sourceLabel, 48) }}</span>
                                </div>
                            @else
                                <div class="rv-source rv-source--empty" aria-hidden="true">
                                    <i class="fa fa-link"></i>
                                    <span>Nguồn bình luận</span>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

                {{-- Nút điều hướng tùy chỉnh --}}
                <button class="rv-nav rv-nav--prev" id="rv-prev" aria-label="Trước">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button class="rv-nav rv-nav--next" id="rv-next" aria-label="Tiếp theo">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>

            {{-- Dots --}}
            <div class="rv-dots-wrap" id="rv-dots"></div>

        </div>

        <div class="rv-gallery-modal" id="rv-gallery-modal" aria-hidden="true">
            <div class="rv-gallery-backdrop" data-rv-gallery-close></div>
            <div class="rv-gallery-dialog" role="dialog" aria-modal="true" aria-label="Ảnh check-in">
                <button type="button" class="rv-gallery-close" data-rv-gallery-close aria-label="Đóng">
                    <i class="fa fa-times"></i>
                </button>
                <div class="rv-gallery-head">
                    <span class="rv-gallery-eyebrow">Ảnh check-in</span>
                    <strong id="rv-gallery-title">Khoảnh khắc của du khách</strong>
                </div>
                <div class="rv-gallery-stage">
                    <button type="button" class="rv-gallery-arrow rv-gallery-arrow--prev" id="rv-gallery-prev"
                        aria-label="Ảnh trước">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <a href="#" target="_blank" class="rv-gallery-main" id="rv-gallery-current-link">
                        <img src="" alt="Ảnh check-in" id="rv-gallery-current-image">
                    </a>
                    <button type="button" class="rv-gallery-arrow rv-gallery-arrow--next" id="rv-gallery-next"
                        aria-label="Ảnh tiếp theo">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
                <div class="rv-gallery-count" id="rv-gallery-count">1 / 1</div>
                <div class="rv-gallery-thumbs" id="rv-gallery-thumbs"></div>
            </div>
        </div>
    </section>

    <style>
        /* ══════════════════════════════════════════
                                   REVIEW SECTION — Bình luận nổi bật
                                ══════════════════════════════════════════ */
        .rv-section {
            position: relative;
            padding: 24px 0 30px !important;
            overflow: hidden;
            background: #eef3f8;
        }

        .rv-section::before {
            content: none;
        }

        .rv-overlay {
            display: none;
        }

        .rv-container {
            position: relative;
            z-index: 2;
            width: min(100% - 44px, 1480px);
            max-width: 1480px;
        }

        /* ── Header ── */
        .rv-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            text-align: left;
            width: min(100%, 1180px);
            margin: 0 auto 16px;
        }

        .rv-heading-copy {
            min-width: 0;
        }

        .rv-sub {
            display: block;
            font-family: 'Times New Roman', Times, serif;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0;
            text-transform: none;
            line-height: 1.18;
            color: var(--primary, #f15d30);
            margin-bottom: 8px;
            position: relative;
            padding: 0;
        }

        .rv-sub::before,
        .rv-sub::after {
            display: none;
        }

        .rv-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 2.28rem;
            font-weight: 800;
            color: #14532d;
            margin: 0 0 6px;
            line-height: 1.25;
            text-shadow: none;
        }

        .rv-desc {
            font-size: 15px;
            color: #6f7787;
            margin: 0 0 14px;
            max-width: 640px;
            white-space: normal;
        }

        /* ── Stats ── */
        .rv-stats {
            display: inline-flex;
            align-items: center;
            gap: 20px;
            background: rgba(255, 255, 255, .9);
            border: 1px solid rgba(134, 239, 172, .55);
            border-radius: 50px;
            padding: 10px 28px;
            box-shadow: 0 12px 32px rgba(22, 101, 52, .08);
        }

        .rv-stat {
            text-align: center;
        }

        .rv-stat__number {
            display: block;
            font-size: 1.4rem;
            font-weight: 800;
            color: #166534;
            line-height: 1;
            margin-bottom: 2px;
        }

        .rv-stat__label {
            font-size: 11px;
            color: #7a8292;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .rv-stat__label .fa-star {
            color: var(--gold, #f7c94b);
            font-size: 10px;
        }

        .rv-stat-divider {
            width: 1px;
            height: 32px;
            background: #bbf7d0;
        }

        /* ── Carousel wrap ── */
        .rv-carousel-wrap {
            position: relative;
            padding: 0 60px;
        }

        @media (max-width: 767px) {
            .rv-carousel-wrap {
                padding: 0 10px;
            }
        }

        /* ── Item card ── */
        .rv-item {
            background: #fff;
            border: 1px solid #d9fbe4;
            border-radius: 20px;
            padding: 22px 24px 20px;
            margin: 8px 4px 16px;
            transition: transform .3s ease, box-shadow .3s ease, background .3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 16px 42px rgba(22, 101, 52, .08);
            min-height: 350px;
            height: calc(100% - 24px);
            display: flex !important;
            flex-direction: column;
        }

        .rv-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #86efac, #22c55e, #86efac);
            border-radius: 20px 20px 0 0;
            opacity: 0;
            transition: opacity .3s;
        }

        .rv-item:hover {
            transform: translateY(-6px);
            background: #fff;
            box-shadow: 0 22px 56px rgba(22, 101, 52, .12), 0 0 0 1px rgba(34, 197, 94, .18);
        }

        .rv-item:hover::before {
            opacity: 1;
        }

        .rv-item--link {
            cursor: pointer;
        }

        .rv-item--link:hover .rv-source {
            opacity: 1;
        }

        /* ── Nguồn bài viết ── */
        .rv-source {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: #15803d;
            background: rgba(220, 252, 231, .9);
            border: 1px solid rgba(134, 239, 172, .8);
            border-radius: 50px;
            padding: 3px 10px;
            margin-bottom: 14px;
            opacity: 1;
            transition: opacity .25s;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            min-height: 24px;
        }

        .rv-source .fa {
            font-size: 10px;
            flex-shrink: 0;
        }

        .rv-source--empty {
            opacity: 0;
            pointer-events: none;
        }

        /* ── Nội dung ── */
        .rv-content {
            font-size: 14.5px;
            line-height: 1.78;
            color: #343b4d;
            margin: 0 0 12px;
            font-style: italic;
            font-weight: 650;
            min-height: 54px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ── Ảnh check-in ── */
        .rv-checkins {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 7px;
            margin: 0 0 12px;
            min-height: 132px;
        }

        .rv-checkins--1 {
            grid-template-columns: 1fr;
        }

        .rv-checkins--2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .rv-checkins--empty {
            grid-template-columns: 1fr;
        }

        .rv-checkin-photo {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            height: 132px;
            border: 1px solid #e7ebf2;
            background: #f4f7fb;
            cursor: zoom-in;
            padding: 0;
            width: 100%;
        }

        .rv-checkin-photo:focus {
            outline: 2px solid rgba(241, 93, 48, .75);
            outline-offset: 2px;
        }

        .rv-checkin-photo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            transition: transform .25s ease, filter .25s ease;
        }

        .rv-item:hover .rv-checkin-photo img {
            transform: scale(1.02);
            filter: saturate(1.08);
        }

        .rv-checkin-more {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(10, 10, 30, .58);
            color: #fff;
            font-size: 18px;
            font-weight: 800;
        }

        .rv-checkin-empty {
            height: 132px;
            border-radius: 10px;
            border: 1px dashed #d9dee9;
            background: #f7f9fc;
            color: #9aa3b2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .rv-checkin-empty .fa {
            font-size: 13px;
        }

        /* ── Gallery modal ── */
        .rv-gallery-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .rv-gallery-modal.is-open {
            display: flex;
        }

        .rv-gallery-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(7, 12, 24, .78);
            backdrop-filter: blur(6px);
        }

        .rv-gallery-dialog {
            position: relative;
            width: min(940px, 100%);
            max-height: min(82vh, 760px);
            overflow: hidden;
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 16px;
            box-shadow: 0 28px 80px rgba(0, 0, 0, .5);
            padding: 22px;
        }

        .rv-gallery-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
            color: #fff;
            cursor: pointer;
        }

        .rv-gallery-head {
            padding-right: 44px;
            margin-bottom: 16px;
        }

        .rv-gallery-eyebrow {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary, #f15d30);
            margin-bottom: 4px;
        }

        .rv-gallery-head strong {
            display: block;
            color: #fff;
            font-size: 20px;
            line-height: 1.3;
        }

        .rv-gallery-stage {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            background: rgba(0, 0, 0, .24);
            min-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rv-gallery-main {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: min(58vh, 520px);
            padding: 10px;
        }

        .rv-gallery-main img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
            border-radius: 10px;
            box-shadow: 0 16px 42px rgba(0, 0, 0, .35);
        }

        .rv-gallery-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 50%;
            background: rgba(15, 23, 42, .68);
            color: #fff;
            cursor: pointer;
            z-index: 2;
            transition: background .2s ease, transform .2s ease;
        }

        .rv-gallery-arrow:hover {
            background: var(--primary, #f15d30);
            transform: translateY(-50%) scale(1.06);
        }

        .rv-gallery-arrow:disabled {
            opacity: .35;
            cursor: default;
        }

        .rv-gallery-arrow:disabled:hover {
            background: rgba(15, 23, 42, .68);
            transform: translateY(-50%);
        }

        .rv-gallery-arrow--prev {
            left: 14px;
        }

        .rv-gallery-arrow--next {
            right: 14px;
        }

        .rv-gallery-count {
            color: rgba(255, 255, 255, .72);
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            margin: 10px 0 12px;
        }

        .rv-gallery-thumbs {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .rv-gallery-thumb {
            width: 68px;
            height: 54px;
            flex: 0 0 68px;
            border: 2px solid transparent;
            border-radius: 8px;
            overflow: hidden;
            padding: 0;
            background: rgba(255, 255, 255, .08);
            cursor: pointer;
        }

        .rv-gallery-thumb.is-active {
            border-color: var(--primary, #f15d30);
        }

        .rv-gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* ── Stars ── */
        .rv-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .rv-stars {
            display: flex;
            gap: 3px;
            flex-shrink: 0;
            margin-bottom: 0;
        }

        .rv-stars .fa-star {
            color: var(--gold, #f7c94b);
            font-size: 13px;
        }

        /* ── Divider ── */
        .rv-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(15, 23, 42, .12), transparent);
            margin-bottom: 14px;
            margin-top: 0;
        }

        /* ── User info ── */
        .rv-user {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .rv-avatar-wrap {
            position: relative;
            width: 46px;
            height: 46px;
            flex-shrink: 0;
        }

        .rv-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid #fff;
            box-shadow: 0 4px 14px rgba(15, 23, 42, .18);
        }

        .rv-avatar-fallback {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary, #f15d30), #ff8c5a);
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            align-items: center;
            justify-content: center;
        }

        .rv-user-info {
            flex: 1;
            min-width: 0;
        }

        .rv-user-name {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #1f2433;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rv-user-time {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #7a8292;
            margin-top: 2px;
        }

        /* ── Nav buttons ── */
        .rv-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e4e8f0;
            color: #1f2433;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .12);
            transition: all .25s ease;
            z-index: 10;
            margin-top: -16px;
        }

        .rv-nav--prev {
            left: 0;
        }

        .rv-nav--next {
            right: 0;
        }

        .rv-nav:hover {
            background: #22c55e;
            border-color: #22c55e;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 6px 20px rgba(34, 197, 94, .35);
        }

        @media (max-width: 767px) {
            .rv-nav {
                display: none;
            }
        }

        /* ── Dots ── */
        .rv-dots-wrap {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }

        .rv-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #d8dee9;
            border: none;
            cursor: pointer;
            padding: 0;
            transition: all .25s ease;
        }

        .rv-dot.active {
            width: 24px;
            border-radius: 4px;
            background: #22c55e;
        }

        /* ── Owl overrides ── */
        .rv-carousel .owl-stage {
            padding: 10px 0;
            display: flex;
        }

        .rv-carousel .owl-item {
            display: flex;
        }

        .rv-carousel .owl-item>* {
            width: 100%;
        }

        .rv-carousel.owl-loaded .owl-nav {
            display: none;
        }

        .rv-carousel.owl-loaded .owl-dots {
            display: none;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .rv-section {
                padding: 22px 0 28px !important;
            }

            .rv-sub {
                font-size: 1.75rem;
            }

            .rv-title {
                font-size: 1.95rem;
            }

            .rv-header {
                width: 100%;
                margin-bottom: 18px;
            }

            .rv-stats {
                gap: 14px;
                padding: 8px 20px;
            }

            .rv-stat__number {
                font-size: 1.2rem;
            }

        }

        @media (max-width: 767px) {
            .rv-section {
                padding: 20px 0 24px !important;
            }

            .rv-header {
                display: block;
            }

            .rv-sub {
                font-size: 1.5rem;
            }

            .rv-title {
                font-size: 1.7rem;
            }

            .rv-desc {
                font-size: 14px;
                white-space: normal;
            }

            .rv-stats {
                flex-direction: column;
                gap: 10px;
                border-radius: 16px;
                padding: 14px 20px;
            }

            .rv-stat-divider {
                width: 48px;
                height: 1px;
            }

            .rv-item {
                padding: 20px 18px 18px;
                border-radius: 14px;
                min-height: 335px;
            }

            .rv-content {
                font-size: 13.5px;
                min-height: 50px;
            }
        }

        @media (max-width: 575px) {
            .rv-title {
                font-size: 1.55rem;
            }

            .rv-sub {
                font-size: 1.38rem;
                letter-spacing: 0;
            }

            .rv-stats {
                gap: 8px;
                padding: 12px 16px;
            }

            .rv-stat__number {
                font-size: 1.1rem;
            }

            .rv-item {
                padding: 18px 14px 14px;
                margin: 6px 2px 12px;
                min-height: 320px;
            }

            .rv-content {
                font-size: 13px;
                margin-bottom: 10px;
                min-height: 48px;
            }

            .rv-checkins,
            .rv-checkin-photo,
            .rv-checkin-empty {
                min-height: 112px;
                height: 112px;
            }

            .rv-gallery-modal {
                padding: 12px;
            }

            .rv-gallery-dialog {
                padding: 18px 14px;
                border-radius: 14px;
                max-height: 86vh;
            }

            .rv-gallery-stage {
                min-height: 300px;
            }

            .rv-gallery-main {
                height: min(54vh, 390px);
                padding: 8px;
            }

            .rv-gallery-arrow {
                width: 38px;
                height: 38px;
            }

            .rv-gallery-arrow--prev {
                left: 8px;
            }

            .rv-gallery-arrow--next {
                right: 8px;
            }

            .rv-gallery-thumb {
                width: 58px;
                height: 46px;
                flex-basis: 58px;
            }

            .rv-gallery-head strong {
                font-size: 17px;
            }

            .rv-avatar,
            .rv-avatar-fallback {
                width: 38px;
                height: 38px;
            }

            .rv-avatar-wrap {
                width: 38px;
                height: 38px;
            }

            .rv-card-top {
                align-items: flex-start;
            }

            .rv-stars .fa-star {
                font-size: 11px;
            }
        }
    </style>

    <script>
        window.addEventListener('load', function () {
            var total = {{ $comments->count() }};
            var $owl = $('#rv-owl');
            var current = 0;

            // Hủy instance cũ từ main.js nếu có
            if ($owl.data('owl.carousel')) {
                $owl.trigger('destroy.owl.carousel');
                $owl.removeClass('owl-loaded owl-drag');
                $owl.find('.owl-stage-outer').children().unwrap();
            }

            var itemsDesktop = Math.min(4, total);
            var itemsTablet = Math.min(2, total);
            var itemsMobile = 1;
            var enableLoop = total > itemsDesktop;

            $owl.owlCarousel({
                loop: enableLoop,
                margin: 20,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                smartSpeed: 600,
                responsive: {
                    0: { items: itemsMobile },
                    480: { items: itemsMobile },
                    640: { items: itemsTablet },
                    992: { items: itemsDesktop }
                }
            });

            // Số trang thực tế
            var pages = Math.ceil(total / itemsDesktop);

            // Tạo dots
            var $dotsWrap = $('#rv-dots');
            $dotsWrap.empty();
            for (var i = 0; i < pages; i++) {
                (function (idx) {
                    $('<button class="rv-dot' + (idx === 0 ? ' active' : '') + '"></button>')
                        .on('click', function () {
                            $owl.trigger('to.owl.carousel', [idx * itemsDesktop, 400]);
                        })
                        .appendTo($dotsWrap);
                })(i);
            }

            // Cập nhật dot active
            function updateDots(itemIndex) {
                var pageIdx = Math.floor(itemIndex / itemsDesktop) % pages;
                $('#rv-dots .rv-dot').removeClass('active').eq(pageIdx).addClass('active');
            }

            // Nút prev / next tùy chỉnh
            $('#rv-prev').on('click', function () { $owl.trigger('prev.owl.carousel'); });
            $('#rv-next').on('click', function () { $owl.trigger('next.owl.carousel'); });

            $('.rv-item--link').on('click', function (event) {
                if ($(event.target).closest('.js-rv-gallery, button').length) {
                    return;
                }

                var href = $(this).data('href');
                if (href) {
                    window.location.href = href;
                }
            });

            var galleryImages = [];
            var galleryIndex = 0;
            var galleryName = 'du khách';

            function closeReviewGallery() {
                $('#rv-gallery-modal').removeClass('is-open').attr('aria-hidden', 'true');
                $('body').css('overflow', '');
            }

            function normalizeGalleryImages(images) {
                if (typeof images === 'string') {
                    try {
                        return JSON.parse(images);
                    } catch (error) {
                        return [];
                    }
                }

                return Array.isArray(images) ? images : [];
            }

            function renderReviewGallery() {
                if (!galleryImages.length) {
                    return;
                }

                var currentSrc = galleryImages[galleryIndex];
                $('#rv-gallery-current-link').attr('href', currentSrc);
                $('#rv-gallery-current-image')
                    .attr('src', currentSrc)
                    .attr('alt', 'Ảnh check-in của ' + galleryName);
                $('#rv-gallery-count').text((galleryIndex + 1) + ' / ' + galleryImages.length);
                $('#rv-gallery-prev, #rv-gallery-next').prop('disabled', galleryImages.length <= 1);

                var $thumbs = $('#rv-gallery-thumbs');
                $thumbs.empty();
                galleryImages.forEach(function (src, index) {
                    $('<button type="button" class="rv-gallery-thumb"></button>')
                        .toggleClass('is-active', index === galleryIndex)
                        .append($('<img>').attr('src', src).attr('alt', 'Ảnh ' + (index + 1)))
                        .on('click', function (event) {
                            event.preventDefault();
                            galleryIndex = index;
                            renderReviewGallery();
                        })
                        .appendTo($thumbs);
                });
            }

            function moveReviewGallery(step) {
                if (!galleryImages.length) {
                    return;
                }

                galleryIndex = (galleryIndex + step + galleryImages.length) % galleryImages.length;
                renderReviewGallery();
            }

            $('.js-rv-gallery').on('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                galleryImages = normalizeGalleryImages($(this).data('images'));
                galleryIndex = parseInt($(this).data('index'), 10) || 0;
                galleryName = $(this).data('name') || 'du khách';

                if (!galleryImages.length) {
                    return;
                }

                galleryIndex = Math.min(Math.max(galleryIndex, 0), galleryImages.length - 1);
                $('#rv-gallery-title').text('Khoảnh khắc của ' + galleryName);
                renderReviewGallery();
                $('#rv-gallery-modal').addClass('is-open').attr('aria-hidden', 'false');
                $('body').css('overflow', 'hidden');
            });

            $('#rv-gallery-prev').on('click', function (event) {
                event.preventDefault();
                moveReviewGallery(-1);
            });

            $('#rv-gallery-next').on('click', function (event) {
                event.preventDefault();
                moveReviewGallery(1);
            });

            $('[data-rv-gallery-close]').on('click', closeReviewGallery);

            $(document).on('keyup', function (event) {
                if (!$('#rv-gallery-modal').hasClass('is-open')) {
                    return;
                }

                if (event.key === 'Escape') {
                    closeReviewGallery();
                } else if (event.key === 'ArrowLeft') {
                    moveReviewGallery(-1);
                } else if (event.key === 'ArrowRight') {
                    moveReviewGallery(1);
                }
            });

            // Đồng bộ dots với carousel
            $owl.on('changed.owl.carousel', function (e) {
                var itemIdx = e.item.index;
                if (typeof itemIdx === 'number' && itemIdx >= 0) {
                    updateDots(itemIdx);
                }
            });
        });
    </script>
@endif