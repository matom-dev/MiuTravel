<li class="cmt-item">
    <div class="cmt-avatar">
        @php
            $avatarSrc = (isset($comment) && !empty($comment->user->avatar))
                ? asset(pare_url_file($comment->user->avatar))
                : asset('page/images/person_1.jpg');
            $userName  = (isset($comment) && !empty($comment->user->name))
                ? $comment->user->name
                : 'Ẩn danh';
            $initials  = mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8');
        @endphp
        <img src="{{ $avatarSrc }}" alt="{{ $userName }}" loading="lazy">
    </div>
    <div class="cmt-body">
        <div class="cmt-header">
            <div class="cmt-author-rating">
                <span class="cmt-name">{{ $userName }}</span>
                <span class="cmt-stars" aria-label="5 sao">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </span>
            </div>
            <span class="cmt-time">
                <i class="fa fa-clock-o"></i>
                {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
            </span>
        </div>
        <div class="cmt-content">
            {!! nl2br(e($comment->cm_content)) !!}
        </div>
        @php
            $commentImages = is_array($comment->cm_images) ? $comment->cm_images : [];
            $commentImageUrls = array_map(function ($image) {
                return asset(pare_url_file($image));
            }, $commentImages);
        @endphp
        @if(!empty($commentImages))
            <div class="cmt-checkin-gallery">
                @foreach($commentImages as $imageIndex => $image)
                    <button type="button"
                            class="cmt-checkin-photo js-cmt-gallery"
                            data-images='@json($commentImageUrls)'
                            data-index="{{ $imageIndex }}"
                            data-name="{{ $userName }}">
                        <img src="{{ asset(pare_url_file($image)) }}" alt="Ảnh check-in của {{ $userName }}" loading="lazy">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</li>

<style>
/* ── Comment list ── */
.comment-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.cmt-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
}

/* Avatar */
.cmt-avatar {
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #f1f5f9;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.cmt-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Body */
.cmt-body {
    flex: 1;
    background: #ffffff;
    border-radius: 14px;
    padding: 14px 18px;
    border: 1px solid #e8edf2;
    border-left: 3px solid var(--primary, #e84c00);
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: box-shadow 0.2s;
}
.cmt-body:hover {
    box-shadow: 0 4px 16px rgba(232,76,0,0.1);
}
.cmt-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    flex-wrap: wrap;
    gap: 4px;
}
.cmt-author-rating {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}
.cmt-name {
    font-size: 14px;
    font-weight: 700;
    color: #1a202c;
}
.cmt-stars {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    color: #f7c94b;
    font-size: 12px;
    white-space: nowrap;
}
.cmt-time {
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 4px;
}
.cmt-content {
    font-size: 14px;
    color: #334155;
    line-height: 1.7;
    word-break: break-word;
    font-weight: 400;
}
.cmt-checkin-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(92px, 1fr));
    gap: 8px;
    margin-top: 12px;
    max-width: 440px;
}
.cmt-checkin-photo {
    display: block;
    overflow: hidden;
    border-radius: 10px;
    border: 1px solid #e8edf2;
    aspect-ratio: 1 / 1;
    background: #f8fafc;
    cursor: zoom-in;
    padding: 0;
    width: 100%;
}
.cmt-checkin-photo:focus { outline: 2px solid var(--primary, #e84c00); outline-offset: 2px; }
.cmt-checkin-photo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    transition: transform .2s ease;
}
.cmt-checkin-photo:hover img {
    transform: scale(1.02);
}

/* Comment check-in lightbox */
.cmt-gallery-modal {
    position: fixed;
    inset: 0;
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 24px;
}
.cmt-gallery-modal.is-open { display: flex; }
.cmt-gallery-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(7,12,24,.78);
    backdrop-filter: blur(6px);
}
.cmt-gallery-dialog {
    position: relative;
    width: min(940px, 100%);
    max-height: min(84vh, 760px);
    overflow: hidden;
    background: #0f172a;
    border: 1px solid rgba(255,255,255,.16);
    border-radius: 16px;
    box-shadow: 0 28px 80px rgba(0,0,0,.5);
    padding: 22px;
}
.cmt-gallery-close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 36px;
    height: 36px;
    border: 0;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    color: #fff;
    cursor: pointer;
}
.cmt-gallery-head {
    padding-right: 44px;
    margin-bottom: 16px;
}
.cmt-gallery-head span {
    display: block;
    color: var(--primary, #e84c00);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 4px;
}
.cmt-gallery-head strong {
    display: block;
    color: #fff;
    font-size: 20px;
    line-height: 1.3;
}
.cmt-gallery-stage {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    background: rgba(0,0,0,.24);
    min-height: 420px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cmt-gallery-main {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: min(58vh, 520px);
    padding: 10px;
}
.cmt-gallery-main img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    display: block;
    border-radius: 10px;
    box-shadow: 0 16px 42px rgba(0,0,0,.35);
}
.cmt-gallery-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 50%;
    background: rgba(15,23,42,.68);
    color: #fff;
    cursor: pointer;
    z-index: 2;
    transition: background .2s ease, transform .2s ease;
}
.cmt-gallery-arrow:hover {
    background: var(--primary, #e84c00);
    transform: translateY(-50%) scale(1.06);
}
.cmt-gallery-arrow:disabled {
    opacity: .35;
    cursor: default;
}
.cmt-gallery-arrow:disabled:hover {
    background: rgba(15,23,42,.68);
    transform: translateY(-50%);
}
.cmt-gallery-arrow--prev { left: 14px; }
.cmt-gallery-arrow--next { right: 14px; }
.cmt-gallery-count {
    color: rgba(255,255,255,.72);
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    margin: 10px 0 12px;
}
.cmt-gallery-thumbs {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 2px;
}
.cmt-gallery-thumb {
    width: 68px;
    height: 54px;
    flex: 0 0 68px;
    border: 2px solid transparent;
    border-radius: 8px;
    overflow: hidden;
    padding: 0;
    background: rgba(255,255,255,.08);
    cursor: pointer;
}
.cmt-gallery-thumb.is-active { border-color: var(--primary, #e84c00); }
.cmt-gallery-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
@media (max-width: 575px) {
    .cmt-gallery-modal { padding: 12px; }
    .cmt-gallery-dialog { padding: 18px 14px; border-radius: 14px; max-height: 86vh; }
    .cmt-gallery-stage { min-height: 300px; }
    .cmt-gallery-main { height: min(54vh, 390px); padding: 8px; }
    .cmt-gallery-arrow { width: 38px; height: 38px; }
    .cmt-gallery-arrow--prev { left: 8px; }
    .cmt-gallery-arrow--next { right: 8px; }
    .cmt-gallery-thumb { width: 58px; height: 46px; flex-basis: 58px; }
    .cmt-gallery-head strong { font-size: 17px; }
}
</style>
