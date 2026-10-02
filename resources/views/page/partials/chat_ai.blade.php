<style>
    #chat-widget { --chat-accent: #c8431b; position: fixed; z-index: 999999; bottom: max(22px, env(safe-area-inset-bottom)); right: 22px; font-family: 'Inter', Arial, sans-serif; color: #253347; line-height: 1.5; }
    #chat-widget * { box-sizing: border-box; }
    #chat-widget [hidden] { display: none !important; }
    #chat-widget button, #chat-widget input { font: inherit; }
    #chat-widget button { cursor: pointer; }
    #chat-widget button:focus-visible { outline: 3px solid #236ca4; outline-offset: 4px; }
    #chat-widget svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
    #chat-toggle { display: flex; align-items: center; gap: 10px; padding: 14px 20px; border: 1px solid #ffffff50; border-radius: 100px; color: #fff; background: linear-gradient(125deg, #df5027, #bf3b18); box-shadow: 0 8px 28px #98331340; font-weight: 600; transition: transform .2s; }
    #chat-toggle:hover { transform: translateY(-3px); }
    #chat-box { width: 390px; max-width: calc(100vw - 32px); height: 580px; max-height: calc(100dvh - 44px); display: flex; flex-direction: column; overflow: hidden; background: #fff; border: 1px solid #e9e5e1; border-radius: 24px; box-shadow: 0 24px 80px #28324130, 0 4px 16px #28324110; animation: chat-appear .2s ease-out; }
    #chat-header { padding: 22px 20px; display: flex; align-items: center; gap: 12px; color: #fff; background: linear-gradient(120deg, #d84c24, #b73616); }
    #chat-widget .chat-avatar { display: grid; place-items: center; width: 44px; height: 44px; border-radius: 15px; background: #ffffff24; border: 1px solid #ffffff35; flex-shrink: 0; }
    #chat-title { margin: 0; color: #fff; font-size: 17px; font-weight: 700; line-height: 1.4; }
    #chat-widget .chat-subtitle { display: block; font-size: 12px; margin-top: 3px; color: #fff0e9; }
    #chat-close { margin-left: auto; display: grid; place-items: center; min-width: 36px; height: 36px; padding: 0; background: #ffffff16; border: 0; border-radius: 50%; color: #fff; }
    #chat-close:hover { background: #ffffff30; }
    #chat-messages { flex: 1; min-height: 0; overflow-y: auto; overscroll-behavior: contain; padding: 22px 18px; display: flex; flex-direction: column; gap: 14px; background: linear-gradient(#fff8f3, #f8fafc 190px); scrollbar-width: thin; scrollbar-color: #dbcec7 transparent; }
    #chat-widget .chat-welcome { margin: 4px 0 8px; }
    #chat-widget .chat-eyebrow { font-size: 10px; font-weight: 700; letter-spacing: 1.8px; color: #a44729; }
    #chat-widget .chat-welcome h3 { margin: 8px 0; font-size: 23px; line-height: 1.35; font-weight: 700; color: #253347; }
    #chat-widget .chat-welcome p { font-size: 14px; color: #647084; margin: 0 0 18px; }
    #chat-widget .chat-suggestions { display: flex; flex-wrap: wrap; gap: 8px; }
    #chat-widget .chat-suggestion { border: 1px solid #ead9cf; background: #fff; border-radius: 12px; padding: 9px 12px; color: #9d391c; font-size: 12px; text-align: left; transition: background .2s; }
    #chat-widget .chat-suggestion:hover { background: #ffede2; }
    #chat-widget .bot-msg, #chat-widget .user-msg { padding: 12px 15px; max-width: 88%; font-size: 14px; line-height: 1.65; overflow-wrap: anywhere; white-space: pre-wrap; }
    #chat-widget .bot-msg { align-self: flex-start; background: #fff; border: 1px solid #e8ebef; border-radius: 4px 17px 17px; color: #334155; }
    #chat-widget .user-msg { align-self: flex-end; background: var(--chat-accent); color: white; border-radius: 17px 17px 4px 17px; }
    #chat-status { margin: 0; padding: 0 20px; background: #f8fafc; color: #677487; font-size: 12px; }
    #chat-status:not(:empty) { padding-top: 8px; padding-bottom: 8px; }
    #chat-input { margin: 0; padding: 15px 16px 12px; border-top: 1px solid #edf0f3; background: #fff; }
    #chat-widget .chat-compose { display: flex; gap: 8px; align-items: center; padding: 6px; border: 1px solid #dce1e7; border-radius: 16px; background: #fafbfc; transition: box-shadow .2s; }
    #chat-widget .chat-compose:focus-within { border-color: #cf542e; box-shadow: 0 0 0 3px #f15d3014; }
    #message-input { min-width: 0; width: 100%; flex: 1; padding: 8px; border: 0; background: transparent; color: #253347; outline: none; font-size: 14px !important; }
    #message-input::placeholder { color: #7c8798; }
    #send-btn { width: 40px; height: 40px; display: grid; place-items: center; flex-shrink: 0; padding: 0; border: 0; border-radius: 12px; background: var(--chat-accent); color: #fff; }
    #send-btn:disabled { opacity: .45; cursor: default; }
    #chat-widget .chat-note { margin: 9px 0 0; text-align: center; color: #7a8491; font-size: 10px; }
    @keyframes chat-appear { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 480px) { #chat-widget { right: 12px; bottom: max(12px, env(safe-area-inset-bottom)); } #chat-box { width: calc(100vw - 24px); max-width: none; max-height: calc(100dvh - 24px - env(safe-area-inset-bottom)); border-radius: 20px; } #chat-toggle { padding: 13px 16px; font-size: 13px; } #message-input { font-size: 16px !important; } }
    @media (prefers-reduced-motion: reduce) { #chat-widget *, #chat-widget *::before { animation: none !important; transition: none !important; } }
</style>

<div id="chat-widget">
    <button id="chat-toggle" type="button" aria-expanded="false" aria-controls="chat-box">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 11.5a8.5 8.5 0 0 1-8.5 8.5H4l-3 2 2-6a8.5 8.5 0 1 1 18-4.5Z"/><path d="M8 10h8M8 14h5"/></svg>
        <span>Chat cùng Miu Travel</span>
    </button>
    <section id="chat-box" aria-labelledby="chat-title" hidden>
        <header id="chat-header">
            <span class="chat-avatar" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m12 3 2.5 6.5L21 12l-6.5 2.5L12 21l-2.5-6.5L3 12l6.5-2.5Z"/></svg></span>
            <div><h2 id="chat-title">Miu Travel</h2><span class="chat-subtitle">Trợ lý du lịch AI của bạn</span></div>
            <button id="chat-close" type="button" aria-label="Đóng trò chuyện"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 7 10 10M17 7 7 17"/></svg></button>
        </header>
        <div id="chat-messages" role="log" aria-label="Nội dung trò chuyện" aria-live="polite" aria-relevant="additions" tabindex="0"></div>
        <p id="chat-status" role="status"></p>
        <form id="chat-input">
            <div class="chat-compose">
                <input type="text" id="message-input" placeholder="Bạn muốn đi đâu?" aria-label="Tin nhắn của bạn" maxlength="2000" autocomplete="off">
                <button id="send-btn" type="submit" aria-label="Gửi tin nhắn" disabled><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m21 3-7 18-4-7-7-4 18-7ZM10 14 21 3"/></svg></button>
            </div>
            <p class="chat-note">Đồng hành cùng bạn trên mỗi chuyến đi · AI hỗ trợ</p>
        </form>
    </section>
</div>

<script>
    $(function () {
        const box = $('#chat-box');
        const input = $('#message-input');
        const messages = $('#chat-messages');
        const status = $('#chat-status');
        let sending = false;
        let loading = false;
        let loaded = false;

        function updateSend() {
            $('#send-btn').prop('disabled', sending || loading || !input.val().trim());
        }
        function scrollToEnd() { messages.scrollTop(messages[0].scrollHeight); }
        function appendOne(message) {
            $('<div>').addClass(message.sender === 'user' ? 'user-msg' : 'bot-msg').text(message.message).appendTo(messages);
            scrollToEnd();
        }
        function welcome() {
            messages.html('<div class="chat-welcome"><span class="chat-eyebrow">CÙNG BẠN KHÁM PHÁ</span><h3>Chuyến đi tiếp theo,<br>bạn muốn đến đâu?</h3><p>Xin chào! Mình có thể giúp bạn tìm tour, khách sạn và gợi ý cho chuyến đi sắp tới.</p><div class="chat-suggestions"><button type="button" class="chat-suggestion">Gợi ý tour Đà Nẵng</button><button type="button" class="chat-suggestion">Tìm khách sạn Đà Lạt</button><button type="button" class="chat-suggestion">Hướng dẫn đặt tour</button></div></div>');
        }
        function closeChat() {
            box.prop('hidden', true);
            $('#chat-toggle').prop('hidden', false).attr('aria-expanded', 'false').trigger('focus');
        }
        $('#chat-toggle').on('click', function () {
            box.prop('hidden', false);
            $(this).prop('hidden', true).attr('aria-expanded', 'true');
            input.trigger('focus');
            if (loaded || loading) return;
            loading = true;
            status.text('Đang tải cuộc trò chuyện…');
            updateSend();
            $.get('/chat/messages').done(function (items) {
                messages.empty();
                if (items && items.length) items.forEach(appendOne);
                else welcome();
                loaded = true;
                status.text('');
            }).fail(function () {
                if (!messages.children().length) welcome();
                status.text('Chưa tải được lịch sử. Đóng và mở lại để thử lại.');
            }).always(function () { loading = false; updateSend(); });
        });
        $('#chat-close').on('click', closeChat);
        $('#chat-widget').on('keydown', function (event) {
            if (event.key === 'Escape' && !box.prop('hidden')) closeChat();
        });
        input.on('input', updateSend);
        messages.on('click', '.chat-suggestion', function () {
            input.val($(this).text()).trigger('input').trigger('focus');
        });
        $('#chat-input').on('submit', function (event) {
            event.preventDefault();
            const text = input.val().trim();
            if (!text || sending || loading) return;
            sending = true;
            updateSend();
            status.text('Miu đang chuẩn bị câu trả lời…');
            $.ajax({
                url: '/chat/send', method: 'POST', data: { message: text },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            }).done(function (response) {
                messages.find('.chat-welcome').remove();
                if (response.user) appendOne(response.user);
                if (response.bot) appendOne(response.bot);
                if (input.val().trim() === text) input.val('');
                loaded = true;
                status.text('');
            }).fail(function () {
                status.text('Không gửi được tin nhắn. Bạn vui lòng thử lại nhé.');
            }).always(function () {
                sending = false;
                updateSend();
            });
        });
    });
</script>
