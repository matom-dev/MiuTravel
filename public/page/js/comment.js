$(function () {

    "use strict";

    $('.btn-comment').click(function (event) {
        event.preventDefault();
        var tour_id = $(this).attr('tour_id');
        var reply_id = $(this).attr('reply_id');
        var article_id = $(this).attr('article_id');
        var hotel_id = $(this).attr('hotel_id');

        // Tìm textarea gần nhất: ưu tiên #hd-comment-msg, sau đó #message
        var $textarea = $(this).closest('div, form').find('textarea').first();
        if ($textarea.length === 0) {
            $textarea = $('#hd-comment-msg').length ? $('#hd-comment-msg') : $('#message');
        }
        var content = $textarea.val();

        if (content == '') {
            $('.text-errors-comment').css('display', 'block');
            return false;
        } else {
            $('.text-errors-comment').css('display', 'none');
        }
        var __that = $(this);
        var formData = new FormData();
        formData.append('tour_id', tour_id || '');
        formData.append('reply_id', reply_id || '');
        formData.append('article_id', article_id || '');
        formData.append('hotel_id', hotel_id || '');
        formData.append('message', content);

        var $rating = $(this).closest('form').find('input[name="rating"]:checked');
        if ($rating.length) {
            formData.append('rating', $rating.val());
        }

        var $fileInput = $(this).closest('form').find('input[type="file"][name="checkin_images[]"]');
        if (!reply_id && $fileInput.length && $fileInput[0].files.length) {
            $.each($fileInput[0].files, function (index, file) {
                formData.append('checkin_images[]', file);
            });
        }

        $.ajax({
            url: urlComment,
            type: 'POST',
            dataType: 'json',
            async: true,
            data: formData,
            processData: false,
            contentType: false,
        }).done(function (result) {

            if (result.code == 200) {
                if (result.html) {
                    $('.comment-list').prepend(result.html);
                    $('.comment-empty').hide();
                }
                $textarea.val('');
                if ($fileInput.length) {
                    $fileInput.val('');
                    $fileInput.closest('.comment-checkin-box').find('.comment-image-preview').empty();
                }
                toastr.success(result.message || 'Gửi bình luận thành công', {timeOut: 3000});
            } else {
                toastr.error('Đã xảy ra lỗi không thể bình luận', {timeOut: 3000});
            }
        }).fail(function (XMLHttpRequest, textStatus, thrownError) {
            var message = XMLHttpRequest.responseJSON && XMLHttpRequest.responseJSON.message
                ? XMLHttpRequest.responseJSON.message
                : 'Đã xảy ra lỗi không thể bình luận';
            toastr.error(message, {timeOut: 3000});
        });
    });

    // Ẩn thông báo lỗi khi người dùng gõ vào bất kỳ textarea bình luận nào
    $(document).on('keyup', '#message, #hd-comment-msg', function () {
        var content = $(this).val();
        if (content == '') {
            $('.text-errors-comment').css('display', 'block');
        } else {
            $('.text-errors-comment').css('display', 'none');
        }
    });

    $(document).on('change', 'input[type="file"][name="checkin_images[]"]', function () {
        var files = Array.prototype.slice.call(this.files || []);
        var $preview = $(this).closest('.comment-checkin-box').find('.comment-image-preview');
        $preview.empty();

        files.slice(0, 5).forEach(function (file) {
            if (!file.type.match('image.*')) {
                return;
            }

            var reader = new FileReader();
            reader.onload = function (event) {
                $('<img>').attr('src', event.target.result).appendTo($preview);
            };
            reader.readAsDataURL(file);
        });

        if (files.length > 5) {
            toastr.warning('Bạn chỉ nên chọn tối đa 5 ảnh check-in', {timeOut: 3000});
        }
    });

    var commentGalleryImages = [];
    var commentGalleryIndex = 0;
    var commentGalleryName = 'du khách';

    function ensureCommentGalleryModal() {
        if ($('#cmt-gallery-modal').length) {
            return;
        }

        $('body').append([
            '<div class="cmt-gallery-modal" id="cmt-gallery-modal" aria-hidden="true">',
            '  <div class="cmt-gallery-backdrop" data-cmt-gallery-close></div>',
            '  <div class="cmt-gallery-dialog" role="dialog" aria-modal="true" aria-label="Ảnh check-in">',
            '    <button type="button" class="cmt-gallery-close" data-cmt-gallery-close aria-label="Đóng"><i class="fa fa-times"></i></button>',
            '    <div class="cmt-gallery-head"><span>Ảnh check-in</span><strong id="cmt-gallery-title">Khoảnh khắc của du khách</strong></div>',
            '    <div class="cmt-gallery-stage">',
            '      <button type="button" class="cmt-gallery-arrow cmt-gallery-arrow--prev" id="cmt-gallery-prev" aria-label="Ảnh trước"><i class="fa fa-chevron-left"></i></button>',
            '      <a href="#" target="_blank" class="cmt-gallery-main" id="cmt-gallery-current-link"><img src="" alt="Ảnh check-in" id="cmt-gallery-current-image"></a>',
            '      <button type="button" class="cmt-gallery-arrow cmt-gallery-arrow--next" id="cmt-gallery-next" aria-label="Ảnh tiếp theo"><i class="fa fa-chevron-right"></i></button>',
            '    </div>',
            '    <div class="cmt-gallery-count" id="cmt-gallery-count">1 / 1</div>',
            '    <div class="cmt-gallery-thumbs" id="cmt-gallery-thumbs"></div>',
            '  </div>',
            '</div>'
        ].join(''));
    }

    function normalizeCommentGalleryImages(images) {
        if (typeof images === 'string') {
            try {
                return JSON.parse(images);
            } catch (error) {
                return [];
            }
        }

        return Array.isArray(images) ? images : [];
    }

    function renderCommentGallery() {
        if (!commentGalleryImages.length) {
            return;
        }

        var currentSrc = commentGalleryImages[commentGalleryIndex];
        $('#cmt-gallery-current-link').attr('href', currentSrc);
        $('#cmt-gallery-current-image')
            .attr('src', currentSrc)
            .attr('alt', 'Ảnh check-in của ' + commentGalleryName);
        $('#cmt-gallery-title').text('Khoảnh khắc của ' + commentGalleryName);
        $('#cmt-gallery-count').text((commentGalleryIndex + 1) + ' / ' + commentGalleryImages.length);
        $('#cmt-gallery-prev, #cmt-gallery-next').prop('disabled', commentGalleryImages.length <= 1);

        var $thumbs = $('#cmt-gallery-thumbs');
        $thumbs.empty();
        commentGalleryImages.forEach(function (src, index) {
            $('<button type="button" class="cmt-gallery-thumb"></button>')
                .toggleClass('is-active', index === commentGalleryIndex)
                .append($('<img>').attr('src', src).attr('alt', 'Ảnh ' + (index + 1)))
                .on('click', function (event) {
                    event.preventDefault();
                    commentGalleryIndex = index;
                    renderCommentGallery();
                })
                .appendTo($thumbs);
        });
    }

    function moveCommentGallery(step) {
        if (!commentGalleryImages.length) {
            return;
        }

        commentGalleryIndex = (commentGalleryIndex + step + commentGalleryImages.length) % commentGalleryImages.length;
        renderCommentGallery();
    }

    function closeCommentGallery() {
        $('#cmt-gallery-modal').removeClass('is-open').attr('aria-hidden', 'true');
        $('body').css('overflow', '');
    }

    $(document).on('click', '.js-cmt-gallery', function (event) {
        event.preventDefault();
        event.stopPropagation();

        ensureCommentGalleryModal();
        commentGalleryImages = normalizeCommentGalleryImages($(this).data('images'));
        commentGalleryIndex = parseInt($(this).data('index'), 10) || 0;
        commentGalleryName = $(this).data('name') || 'du khách';

        if (!commentGalleryImages.length) {
            return;
        }

        commentGalleryIndex = Math.min(Math.max(commentGalleryIndex, 0), commentGalleryImages.length - 1);
        renderCommentGallery();
        $('#cmt-gallery-modal').addClass('is-open').attr('aria-hidden', 'false');
        $('body').css('overflow', 'hidden');
    });

    $(document).on('click', '#cmt-gallery-prev', function (event) {
        event.preventDefault();
        moveCommentGallery(-1);
    });

    $(document).on('click', '#cmt-gallery-next', function (event) {
        event.preventDefault();
        moveCommentGallery(1);
    });

    $(document).on('click', '[data-cmt-gallery-close]', closeCommentGallery);

    $(document).on('keyup', function (event) {
        if (!$('#cmt-gallery-modal').hasClass('is-open')) {
            return;
        }

        if (event.key === 'Escape') {
            closeCommentGallery();
        } else if (event.key === 'ArrowLeft') {
            moveCommentGallery(-1);
        } else if (event.key === 'ArrowRight') {
            moveCommentGallery(1);
        }
    });
})
