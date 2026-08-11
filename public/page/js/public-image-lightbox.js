(function ($) {
    'use strict';

    var images = [];
    var index = 0;
    var title = 'Xem ảnh';

    function ensureLightbox() {
        if ($('#public-image-lightbox').length) {
            return;
        }

        $('body').append([
            '<div class="public-image-lightbox" id="public-image-lightbox" aria-hidden="true">',
            '  <div class="public-image-lightbox__backdrop" data-public-lightbox-close></div>',
            '  <div class="public-image-lightbox__dialog" role="dialog" aria-modal="true" aria-label="Xem ảnh">',
            '    <button type="button" class="public-image-lightbox__close" data-public-lightbox-close aria-label="Đóng"><i class="fa fa-times"></i></button>',
            '    <div class="public-image-lightbox__title" id="public-image-lightbox-title">Xem ảnh</div>',
            '    <div class="public-image-lightbox__stage">',
            '      <button type="button" class="public-image-lightbox__arrow public-image-lightbox__arrow--prev" id="public-lightbox-prev" aria-label="Ảnh trước"><i class="fa fa-chevron-left"></i></button>',
            '      <a href="#" target="_blank" class="public-image-lightbox__main" id="public-lightbox-link"><img src="" alt="Xem ảnh" id="public-lightbox-img"></a>',
            '      <button type="button" class="public-image-lightbox__arrow public-image-lightbox__arrow--next" id="public-lightbox-next" aria-label="Ảnh tiếp theo"><i class="fa fa-chevron-right"></i></button>',
            '    </div>',
            '    <div class="public-image-lightbox__count" id="public-lightbox-count">1 / 1</div>',
            '    <div class="public-image-lightbox__thumbs" id="public-lightbox-thumbs"></div>',
            '  </div>',
            '</div>'
        ].join(''));
    }

    function parseImages(value) {
        if (typeof value === 'string') {
            try {
                return JSON.parse(value);
            } catch (error) {
                return [];
            }
        }

        return Array.isArray(value) ? value : [];
    }

    function render() {
        if (!images.length) {
            return;
        }

        var src = images[index];
        $('#public-image-lightbox-title').text(title);
        $('#public-lightbox-link').attr('href', src);
        $('#public-lightbox-img').attr('src', src).attr('alt', title);
        $('#public-lightbox-count').text((index + 1) + ' / ' + images.length);
        $('#public-lightbox-prev, #public-lightbox-next').prop('disabled', images.length <= 1);

        var $thumbs = $('#public-lightbox-thumbs');
        $thumbs.empty();
        images.forEach(function (src, thumbIndex) {
            $('<button type="button" class="public-image-lightbox__thumb"></button>')
                .toggleClass('is-active', thumbIndex === index)
                .append($('<img>').attr('src', src).attr('alt', 'Ảnh ' + (thumbIndex + 1)))
                .on('click', function () {
                    index = thumbIndex;
                    render();
                })
                .appendTo($thumbs);
        });
    }

    function move(step) {
        if (!images.length) {
            return;
        }

        index = (index + step + images.length) % images.length;
        render();
    }

    function close() {
        $('#public-image-lightbox').removeClass('is-open').attr('aria-hidden', 'true');
        $('body').css('overflow', '');
    }

    $(document).on('click', '.js-public-gallery', function (event) {
        event.preventDefault();
        event.stopPropagation();

        ensureLightbox();

        images = parseImages($(this).data('public-images'));
        if (!images.length) {
            var src = $(this).attr('src');
            images = src ? [src] : [];
        }

        index = parseInt($(this).attr('data-public-index'), 10) || 0;
        title = $(this).data('public-title') || $(this).attr('alt') || 'Xem ảnh';

        if (!images.length) {
            return;
        }

        index = Math.min(Math.max(index, 0), images.length - 1);
        render();
        $('#public-image-lightbox').addClass('is-open').attr('aria-hidden', 'false');
        $('body').css('overflow', 'hidden');
    });

    $(document).on('click', '#public-lightbox-prev', function () {
        move(-1);
    });

    $(document).on('click', '#public-lightbox-next', function () {
        move(1);
    });

    $(document).on('click', '[data-public-lightbox-close]', close);

    $(document).on('keyup', function (event) {
        if (!$('#public-image-lightbox').hasClass('is-open')) {
            return;
        }

        if (event.key === 'Escape') {
            close();
        } else if (event.key === 'ArrowLeft') {
            move(-1);
        } else if (event.key === 'ArrowRight') {
            move(1);
        }
    });
})(jQuery);
