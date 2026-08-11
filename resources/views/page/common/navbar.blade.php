<style>
    /* ═══════════════════════════════════════════════════
   NAVBAR — PREMIUM REDESIGN v2
═══════════════════════════════════════════════════ */

    /* Fonts are loaded via <link> in head.blade.php — no @import needed here */

    #ftco-navbar {
        display: block;
        background: #ffffff !important;
        backdrop-filter: blur(18px) saturate(160%);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        padding: 0;
        transition: box-shadow .25s ease, border-color .25s ease;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        margin-top: 0 !important;
        z-index: 9999;
        border: none;
        border-bottom: 1px solid rgba(15, 23, 42, .08);
        font-family: var(--miu-font-family, 'SFProDisplay', 'SF Pro Display', 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif);
        min-height: 84px;
        box-shadow: none;
    }

    #ftco-navbar.scrolled,
    #ftco-navbar.scrolled-light,
    #ftco-navbar.is-home-nav:not(.scrolled) {
        background: #ffffff !important;
        margin-top: 0 !important;
        backdrop-filter: blur(18px) saturate(160%);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        border-bottom-color: rgba(15, 23, 42, .08);
        box-shadow: none;
    }

    #ftco-navbar.is-home-nav:not(.scrolled) .nav-main-links>.nav-item>.nav-link {
        color: #263445 !important;
        text-shadow: none;
    }

    #ftco-navbar.is-home-nav:not(.scrolled) .nav-main-links>.nav-item>.nav-link:hover,
    #ftco-navbar.is-home-nav:not(.scrolled) .nav-main-links>.nav-item>.nav-link.active-nav {
        color: #f15d30 !important;
        text-shadow: none;
    }

    #ftco-navbar.scrolled-light .brand-miu {
        color: #0f6a58 !important;
        text-shadow: none;
    }

    #ftco-navbar.awake,
    #ftco-navbar.sleep,
    #ftco-navbar.scrolled.awake,
    #ftco-navbar.scrolled.sleep {
        margin-top: 0 !important;
        top: 0 !important;
        transform: none !important;
    }

    #ftco-navbar.scrolled-light .brand-sub {
        color: #f15d30 !important;
    }

    #ftco-navbar.scrolled-light .nav-link {
        color: #263445 !important;
    }

    #ftco-navbar.scrolled-light .nav-link:hover {
        color: #f15d30 !important;
        background: transparent !important;
    }

    #ftco-navbar.scrolled-light .nav-link.active-nav {
        color: #f15d30 !important;
        background: transparent !important;
        font-weight: 500;
    }

    #ftco-navbar.scrolled-light .nav-link.active-nav::after {
        display: block !important;
    }

    #ftco-navbar.scrolled-light .navbar-toggler {
        border-color: rgba(15, 23, 42, .18) !important;
    }

    #ftco-navbar.scrolled-light .toggler-icon {
        background: #263445;
    }

    #ftco-navbar.scrolled-light .nav-divider {
        background: rgba(15, 23, 42, .12);
    }

    #ftco-navbar.scrolled-light .btn-nav-logout {
        border-color: rgba(15, 23, 42, .14);
        color: #475569;
    }

    #ftco-navbar.scrolled-light .btn-nav-logout:hover {
        background: #fff;
        color: #f15d30;
    }

    #ftco-navbar.scrolled-light .nav-user-chip {
        border-color: rgba(255, 255, 255, .18);
        color: #475569;
        background: #fff;
    }

    #ftco-navbar.scrolled-light .nav-user-chip:hover {
        color: #f15d30;
        background: #fff;
    }

    #ftco-navbar>.container {
        position: relative;
        display: flex;
        align-items: center;
        max-width: 1600px;
        width: min(calc(100% - 44px), 1600px);
        min-height: 84px;
        padding-left: 0;
        padding-right: 0;
        margin-left: auto;
        margin-right: auto;
        gap: 36px;
    }

    /* ── Brand ── */
    .navbar-brand {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        line-height: 1;
        align-self: stretch;
        min-height: 84px;
        padding: 0;
        text-decoration: none !important;
        gap: 6px;
        transition: transform .28s cubic-bezier(.4, 0, .2, 1), opacity .28s ease;
        flex-shrink: 0;
        margin-left: 72px;
        margin-right: 0;
    }

    .navbar-brand:hover {
        transform: translateY(-1px);
        opacity: .88;
        text-decoration: none !important;
    }

    #ftco-navbar .brand-logo {
        display: block;
        width: auto;
        max-width: 330px;
        max-height: 84px;
        object-fit: contain;
    }

    /* "MIU TRAVEL" — một dòng */
    #ftco-navbar .brand-main {
        display: flex !important;
        align-items: center !important;
        gap: 5px !important;
        white-space: nowrap !important;
        line-height: 1 !important;
    }

    /* "MIU" — trắng đậm */
    #ftco-navbar .brand-miu {
        font-family: inherit !important;
        font-size: 2.08rem !important;
        font-weight: 800 !important;
        color: #0f6a58 !important;
        letter-spacing: 0 !important;
        line-height: 1 !important;
        white-space: nowrap !important;
    }

    /* "TRAVEL" — gradient cam */
    #ftco-navbar .brand-travel {
        font-family: inherit !important;
        font-size: 2.08rem !important;
        font-weight: 800 !important;
        background: linear-gradient(135deg, #2f8b5f 0%, #f15d30 100%) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        background-clip: text !important;
        letter-spacing: 0 !important;
        line-height: 1 !important;
        white-space: nowrap !important;
        transition: filter .3s;
    }

    #ftco-navbar .navbar-brand:hover .brand-travel {
        filter: brightness(1.15);
    }

    /* Subtitle */
    #ftco-navbar .brand-sub {
        font-family: inherit !important;
        font-size: 10px !important;
        font-weight: 800 !important;
        letter-spacing: 2.8px !important;
        text-transform: uppercase !important;
        color: #f15d30 !important;
        white-space: nowrap !important;
        line-height: 1 !important;
        text-align: center !important;
    }

    /* ── Nav inner ── */
    .navbar-nav-inner {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .nav-main-links {
        flex: 1 1 auto;
        min-width: 0;
        justify-content: center;
    }

    .nav-auth-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-left: auto !important;
        padding-left: 20px;
        flex: 0 0 auto;
    }

    .nav-auth-actions .nav-item {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    /* ── Nav links ── */
    #ftco-navbar .nav-main-links>.nav-item {
        margin: 0 !important;
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: inherit;
        font-size: 19px;
        font-weight: 700;
        color: #403E3A !important;
        height: 54px;
        padding: 0 18px !important;
        margin: 0 !important;
        border-radius: 0;
        border: 1px solid transparent;
        background: transparent !important;
        box-shadow: none !important;
        position: relative;
        transition: color .22s, background .22s, transform .18s;
        white-space: nowrap;
        text-decoration: none;
        letter-spacing: 0;
        line-height: 1.5;
        min-width: auto;
        text-align: center;
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link.nav-service-toggle {
        gap: 4px;
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link.nav-service-toggle.dropdown-toggle::after {
        display: block !important;
        border: 0 !important;
        margin-left: 0;
        vertical-align: initial;
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link.nav-service-toggle::before {
        display: none !important;
    }

    .nav-service-caret {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 14px;
        height: 14px;
        font-size: 12px;
        line-height: 1;
        transform: translateY(1px);
        transition: transform .2s ease;
    }

    .nav-item.dropdown.show .nav-service-caret {
        transform: translateY(1px) rotate(180deg);
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link::after {
        display: block !important;
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 6px;
        height: 3px;
        border-radius: 999px;
        background: #f15d30;
        opacity: 0;
        transform: scaleX(.45);
        transition: opacity .2s ease, transform .2s ease;
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link:hover {
        color: #f15d30 !important;
        background: transparent !important;
        border-color: transparent;
        transform: none;
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link:hover::after {
        display: block !important;
        opacity: 1;
        transform: scaleX(1);
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link.active-nav {
        color: #f15d30 !important;
        background: transparent !important;
        font-weight: 800;
        border-color: transparent;
        box-shadow: none !important;
    }

    #ftco-navbar .nav-main-links>.nav-item>.nav-link.active-nav::after {
        display: block !important;
        opacity: 1;
        transform: scaleX(1);
    }

    .nav-service-menu {
        min-width: 260px;
        padding: 10px;
        margin-top: 14px;
        border: 1px solid rgba(15, 23, 42, .08) !important;
        border-radius: 12px;
        background: #ffffff !important;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .14);
    }

    .nav-service-menu::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: -14px;
        height: 14px;
    }

    .nav-service-menu .dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 46px;
        padding: 10px 14px;
        border-radius: 10px;
        color: #334155;
        font-family: inherit;
        font-size: 16px;
        font-weight: 500;
        line-height: 1.5;
        text-decoration: none;
        background: transparent !important;
        transition: background .2s, color .2s, transform .18s;
    }

    .nav-service-menu .dropdown-item i {
        width: 22px;
        font-size: 16px;
        color: #ff7a52;
        text-align: center;
    }

    .nav-service-menu .dropdown-item:hover,
    .nav-service-menu .dropdown-item.active-service {
        color: #f15d30 !important;
        background: #fff3ed !important;
        transform: none;
    }

    .nav-tour-menu {
        min-width: 280px;
        max-height: min(70vh, 430px);
        overflow-y: auto;
    }

    .nav-tour-menu::-webkit-scrollbar {
        width: 6px;
    }

    .nav-tour-menu::-webkit-scrollbar-thumb {
        background: rgba(241, 93, 48, .35);
        border-radius: 999px;
    }

    /* Override Bootstrap .active trên nav-item tránh nền xấu */
    .navbar-light .navbar-nav .nav-item.active>.nav-link,
    .navbar-light .navbar-nav .active>.nav-link,
    .navbar-light .navbar-nav .nav-link.active {
        background: transparent !important;
        color: #f15d30 !important;
    }

    /* ── Logout button ── */
    .btn-nav-logout {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 12px 18px;
        border-radius: 999px;
        border: 1px solid rgba(15, 23, 42, .12);
        font-family: inherit;
        font-size: 16px;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        transition: all .2s;
        white-space: nowrap;
        cursor: pointer;
        background: #fff;
    }

    .btn-nav-logout:hover {
        background: #fff;
        border-color: rgba(241, 93, 48, .22);
        color: #f15d30;
        text-decoration: none;
    }

    .btn-nav-logout .logout-icon {
        font-size: 14px;
        opacity: .8;
    }

    /* ── Auth buttons ── */
    .btn-nav-login {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 12px 22px;
        border-radius: 999px;
        border: 1.5px solid rgba(15, 23, 42, .12);
        font-size: 16px;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        transition: all .2s;
        white-space: nowrap;
        font-family: inherit;
        background: #fff;
    }

    .btn-nav-login:hover {
        background: #fff;
        border-color: rgba(241, 93, 48, .22);
        color: #f15d30;
        text-decoration: none;
    }

    .btn-nav-reg {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 12px 24px;
        border-radius: 999px;
        background: #f15d30;
        border: 1.5px solid transparent;
        font-size: 16px;
        font-weight: 500;
        color: #fff;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s, filter .2s;
        white-space: nowrap;
        font-family: inherit;
        box-shadow: 0 8px 20px rgba(241, 93, 48, .22);
    }

    .btn-nav-reg:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(241, 93, 48, .28);
        filter: brightness(1.08);
        color: #fff;
        text-decoration: none;
    }

    /* ── User chip (logged in) ── */
    .nav-user-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 16px 7px 7px;
        border-radius: 50px;
        border: 1.5px solid rgba(15, 23, 42, .12);
        color: #475569;
        text-decoration: none;
        font-family: inherit;
        font-size: 16px;
        font-weight: 500;
        transition: all .22s;
        white-space: nowrap;
        background: #fff;
    }

    .nav-user-chip:hover {
        background: #fff;
        border-color: rgba(241, 93, 48, .22);
        color: #f15d30;
        text-decoration: none;
    }

    .nav-user-av-wrap {
        position: relative;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
    }

    .nav-user-av-wrap::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff7a52, #f15d30, #ff9a6c);
        z-index: 0;
    }

    .nav-user-av {
        position: relative;
        z-index: 1;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid transparent;
        background: rgba(255, 255, 255, .2);
        display: block;
    }

    .nav-notify {
        position: relative;
    }

    .nav-notify-toggle {
        position: relative;
        width: 50px;
        height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        border: 1px solid rgba(15, 23, 42, .12);
        background: #fff;
        color: #17443d;
        text-decoration: none;
        transition: all .2s;
    }

    .nav-notify-toggle:hover {
        color: #f15d30;
        background: #fff;
        text-decoration: none;
    }

    .nav-notify-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        min-width: 19px;
        height: 19px;
        padding: 0 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #f15d30;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(241, 93, 48, .35);
    }

    .nav-notify-menu {
        width: 350px;
        max-width: calc(100vw - 24px);
        padding: 0;
        border: 0;
        border-radius: 10px;
        box-shadow: 0 18px 48px rgba(0, 0, 0, .2);
        overflow: hidden;
        font-family: inherit;
    }

    .nav-notify-head {
        padding: 13px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #172033;
        font-size: 14px;
        font-weight: 800;
    }

    .nav-notify-head button {
        border: 0;
        background: none;
        color: #f15d30;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
    }

    .nav-notify-item {
        width: 100%;
        display: flex;
        gap: 11px;
        padding: 13px 15px;
        border: 0;
        background: #fff;
        text-align: left;
        cursor: pointer;
    }

    .nav-notify-item.is-unread {
        background: #fff7ed;
    }

    .nav-notify-item:hover {
        background: #fff1e8;
    }

    .nav-notify-item i {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #fff1e8;
        color: #f15d30;
    }

    .nav-notify-item strong,
    .nav-notify-item small,
    .nav-notify-item em {
        display: block;
        line-height: 1.4;
    }

    .nav-notify-item strong {
        color: #172033;
        font-size: 13px;
        font-weight: 800;
    }

    .nav-notify-item small {
        color: #64748b;
        font-size: 12px;
        margin-top: 2px;
    }

    .nav-notify-item em {
        color: #94a3b8;
        font-size: 11px;
        font-style: normal;
        margin-top: 5px;
    }

    .nav-notify-empty {
        padding: 24px 15px;
        text-align: center;
        color: #94a3b8;
        font-size: 13px;
    }

    /* ── Divider ── */
    .nav-divider {
        width: 1px;
        height: 20px;
        background: rgba(255, 255, 255, .15);
        margin: 0 8px;
        flex-shrink: 0;
    }

    @media (min-width: 992px) {
        #ftco-nav {
            flex: 1 1 auto !important;
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            min-width: 0;
            width: auto;
        }

        .navbar-nav-inner {
            margin-left: 0 !important;
            justify-content: center !important;
        }

        .nav-auth-actions {
            margin-left: 18px !important;
        }

        .nav-auth-item {
            flex-shrink: 0;
        }
    }

    /* ── Toggler ── */
    .navbar-toggler {
        border: 1.5px solid rgba(15, 23, 42, .18) !important;
        padding: 7px 10px;
        border-radius: 9px;
        background: #fff;
        cursor: pointer;
        outline: none !important;
        box-shadow: none !important;
        transition: background .2s, border-color .2s;
    }

    .navbar-toggler:hover {
        background: #f8fafc;
        border-color: rgba(15, 23, 42, .28) !important;
    }

    .toggler-icon {
        display: block;
        width: 19px;
        height: 1.5px;
        background: #263445;
        border-radius: 2px;
        margin: 4px 0;
        transition: all .25s;
    }

    /* ── Body padding ── */
    body {
        padding-top: 84px;
    }

    /* ── Responsive ── */
    @media (max-width: 991px) {
        #ftco-navbar {
            background: #ffffff !important;
            backdrop-filter: blur(20px);
            border-bottom-color: rgba(15, 23, 42, .08);
            min-height: 86px;
            padding: 0;
        }

        .navbar-collapse {
            background: #ffffff;
            border-radius: 14px;
            margin-top: 10px;
            padding: 10px 8px;
            border: 1px solid rgba(15, 23, 42, .08);
            box-shadow: 0 16px 48px rgba(15, 23, 42, .12);
            max-height: 80vh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .navbar-nav-inner {
            flex-direction: column;
            align-items: stretch;
            gap: 2px;
            width: auto;
        }

        .nav-auth-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 2px;
            margin-left: 0 !important;
            padding-left: 0;
            padding-top: 8px;
            margin-top: 8px;
            border-top: 1px solid rgba(15, 23, 42, .08);
        }

        .nav-service-menu {
            position: static !important;
            float: none;
            width: 100%;
            min-width: 0;
            margin: 4px 0 8px;
            transform: none !important;
            background: #fff;
            box-shadow: none;
        }

        .nav-auth-actions .nav-item,
        .nav-auth-item {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100%;
        }

        .btn-nav-login,
        .btn-nav-reg,
        .btn-nav-logout {
            justify-content: center;
            margin: 4px 0;
            width: 100%;
        }

        #ftco-navbar .nav-main-links>.nav-item>.nav-link {
            font-size: 18px;
            justify-content: flex-start;
            width: 100%;
            height: 48px;
            padding: 11px 15px !important;
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        #ftco-navbar .nav-main-links>.nav-item>.nav-link::after {
            display: none !important;
        }

        #ftco-navbar .nav-main-links>.nav-item>.nav-link.active-nav {
            color: #f15d30 !important;
            background: linear-gradient(135deg, #ff7a52, #f15d30) !important;
            color: #fff !important;
            border-left: 0;
            padding-left: 15px !important;
        }

        .nav-user-chip {
            justify-content: center;
            border-radius: 10px;
            padding: 10px 14px;
            min-height: 44px;
        }

        .navbar-brand {
            margin-left: 16px;
            min-height: 86px;
        }

        #ftco-navbar>.container {
            width: min(calc(100% - 28px), 1580px);
            min-height: 86px;
            padding-left: 0;
            padding-right: 0;
            gap: 0;
        }

        body {
            padding-top: 86px;
        }
    }

    @media (min-width: 992px) and (max-width: 1280px) {
        #ftco-navbar .brand-miu,
        #ftco-navbar .brand-travel {
            font-size: 1.72rem !important;
        }

        #ftco-navbar .brand-logo {
            max-width: 255px;
            max-height: 80px;
        }

        #ftco-navbar .brand-sub {
            font-size: 8px !important;
            letter-spacing: 2px !important;
        }

        .navbar-nav-inner {
            gap: 0;
            margin-left: 0 !important;
        }

        #ftco-navbar .nav-main-links>.nav-item>.nav-link {
            height: 46px;
            padding: 0 14px !important;
            font-size: 16px;
        }

        .nav-auth-actions {
            gap: 6px;
            padding-left: 8px;
        }

        .nav-user-chip {
            max-width: 128px;
            overflow: hidden;
        }

        .btn-nav-logout {
            width: 42px;
            height: 42px;
            padding: 0;
            justify-content: center;
        }

        .btn-nav-logout .logout-icon {
            margin: 0;
        }

        .btn-nav-logout {
            font-size: 0;
        }
    }

    /* ── Small Mobile Brand ── */
    @media (max-width: 479px) {

        #ftco-navbar .brand-miu,
        #ftco-navbar .brand-travel {
            font-size: 1.8rem !important;
        }

        #ftco-navbar .brand-logo {
            max-width: 220px;
            max-height: 72px;
        }

        #ftco-navbar .brand-sub {
            font-size: 9px !important;
            letter-spacing: 2px !important;
        }

        .navbar-toggler {
            padding: 6px 9px;
        }
    }

    @media (max-width: 359px) {

        #ftco-navbar .brand-miu,
        #ftco-navbar .brand-travel {
            font-size: 1.55rem !important;
        }

        #ftco-navbar .brand-logo {
            max-width: 184px;
            max-height: 64px;
        }

        #ftco-navbar .container {
            padding: 0 12px;
        }
    }
</style>

<nav id="ftco-navbar" class="navbar navbar-expand-lg navbar-light ftco_navbar ftco-navbar-light {{ request()->is('/') ? 'is-home-nav' : '' }}">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('page.home') }}">
            <img class="brand-logo" src="{{ asset('page/images/logo.png') }}" alt="MIU Travel">
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon"></span>
            <span class="toggler-icon"></span>
            <span class="toggler-icon"></span>
        </button>

        {{-- Nav --}}
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav align-items-lg-center navbar-nav-inner nav-main-links">

                <li class="nav-item">
                    <a href="{{ route('about.us') }}"
                        class="nav-link {{ request()->is('ve-chung-toi.html') || request()->is('lien-he.html') ? 'active-nav' : '' }}">
                        <i class="fa fa-info-circle d-lg-none mr-1"></i>Giới thiệu
                    </a>
                </li>

                @php
                    $tourActive = request()->is('tour.html') || request()->is('tour/*');
                    $activeTourLocationId = (string) request('location_id');
                    $tourLocations = $tourLocations ?? collect();
                @endphp
                <li class="nav-item dropdown">
                    <a href="{{ route('tour') }}"
                        class="nav-link nav-service-toggle dropdown-toggle {{ $tourActive ? 'active-nav' : '' }}"
                        data-toggle="dropdown">
                        <i class="fa fa-compass d-lg-none mr-1"></i>
                        <span>Tour</span>
                        <span class="nav-service-caret" aria-hidden="true">
                            <i class="fa fa-angle-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu nav-service-menu nav-tour-menu">
                        @forelse($tourLocations as $location)
                            <a href="{{ route('tour', ['location_id' => $location->id]) }}"
                               class="dropdown-item {{ $activeTourLocationId === (string) $location->id ? 'active-service' : '' }}">
                                <i class="fa fa-map-marker"></i> {{ $location->l_name }}
                            </a>
                        @empty
                            <a href="{{ route('tour') }}" class="dropdown-item">
                                <i class="fa fa-map-o"></i> Đang cập nhật địa điểm
                            </a>
                        @endforelse
                    </div>
                </li>

                @php
                    $serviceActive = request()->is('khach-san.html')
                        || request()->is('khach-san/*')
                        || request()->is('thue-xe.html')
                        || request()->is('thue-xe/*')
                        || request()->is('dich-vu.html');
                    $experienceArticleActive = request()->is('kinh-nghiem/*');
                @endphp
                <li class="nav-item dropdown">
                    <a href="{{ route('service.hub') }}" class="nav-link nav-service-toggle dropdown-toggle {{ $serviceActive ? 'active-nav' : '' }}" data-toggle="dropdown">
                        <i class="fa fa-th-large d-lg-none mr-1"></i>
                        <span>Dịch vụ</span>
                        <span class="nav-service-caret" aria-hidden="true">
                            <i class="fa fa-angle-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu nav-service-menu">
                        <a href="{{ route('hotel') }}"
                           class="dropdown-item {{ request()->is('khach-san.html') || request()->is('khach-san/*') ? 'active-service' : '' }}">
                            <i class="fa fa-building"></i> Khách sạn
                        </a>
                        <a href="{{ route('car.rental') }}"
                           class="dropdown-item {{ request()->is('thue-xe.html') || request()->is('thue-xe/*') ? 'active-service' : '' }}">
                            <i class="fa fa-car"></i> Thuê xe
                        </a>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('articles.index') }}"
                        class="nav-link {{ request()->is('tin-tuc.html') || request()->is('tin-tuc/*') ? 'active-nav' : '' }}">
                        <i class="fa fa-newspaper-o d-lg-none mr-1"></i>Tin tức
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link nav-service-toggle dropdown-toggle {{ $experienceArticleActive ? 'active-nav' : '' }}" data-toggle="dropdown">
                        <i class="fa fa-lightbulb-o d-lg-none mr-1"></i>
                        <span>Cẩm nang</span>
                        <span class="nav-service-caret" aria-hidden="true">
                            <i class="fa fa-angle-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu nav-service-menu">
                        <a href="{{ route('articles.category', 'kinh-nghiem-du-lich') }}"
                           class="dropdown-item {{ request()->is('kinh-nghiem/kinh-nghiem-du-lich.html') ? 'active-service' : '' }}">
                            <i class="fa fa-map-o"></i> Kinh nghiệm Du lịch
                        </a>
                        <a href="{{ route('articles.category', 'dac-san') }}"
                           class="dropdown-item {{ request()->is('kinh-nghiem/dac-san.html') ? 'active-service' : '' }}">
                            <i class="fa fa-cutlery"></i> Đặc sản địa phương
                        </a>
                    </div>
                </li>

            </ul>

            <ul class="navbar-nav align-items-lg-center nav-auth-actions">
                {{-- Auth --}}
                @if (Auth::guard('users')->check())
                    @php $user = Auth::guard('users')->user(); @endphp
                    <li class="nav-item dropdown nav-notify nav-auth-item">
                        <a href="#" class="nav-notify-toggle" data-toggle="dropdown" title="Thông báo">
                            <i class="fa fa-bell-o"></i>
                            @if(($userUnreadNotifications ?? 0) > 0)
                                <span
                                    class="nav-notify-badge">{{ $userUnreadNotifications > 9 ? '9+' : $userUnreadNotifications }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right nav-notify-menu">
                            <div class="nav-notify-head">
                                <span>Thông báo</span>
                                @if(($userUnreadNotifications ?? 0) > 0)
                                    <form method="POST" action="{{ route('page.notifications.read') }}" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                        <button type="submit">Đã đọc tất cả</button>
                                    </form>
                                @endif
                            </div>
                            <div class="dropdown-divider m-0"></div>

                            @forelse(($userNotifications ?? collect()) as $notification)
                                <form method="POST" action="{{ route('page.notifications.read', $notification->id) }}"
                                    style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="redirect_to"
                                        value="{{ $notification->url ?: url()->current() }}">
                                    <button type="submit"
                                        class="nav-notify-item {{ $notification->read_at ? '' : 'is-unread' }}">
                                        <i class="fa fa-calendar-check-o"></i>
                                        <span>
                                            <strong>{{ $notification->title }}</strong>
                                            <small>{{ $notification->message }}</small>
                                            <em>{{ $notification->created_at->diffForHumans() }}</em>
                                        </span>
                                    </button>
                                </form>
                                <div class="dropdown-divider m-0"></div>
                            @empty
                                <div class="nav-notify-empty">
                                    <i class="fa fa-bell-o"></i>
                                    <div>Chưa có thông báo mới</div>
                                </div>
                            @endforelse
                        </div>
                    </li>
                    <li class="nav-item nav-auth-item">
                        <a href="{{ route('info.account') }}"
                            class="nav-user-chip {{ request()->is('thong-tin-tai-khoan.html') || request()->is('thay-doi-mat-khau.html') || request()->is('danh-sach-tour.html') ? 'active-nav' : '' }}"
                            title="{{ $user->name }}">
                            <span class="nav-user-av-wrap">
                                <img class="nav-user-av"
                                    src="{{ $user->avatar ? asset(pare_url_file($user->avatar)) : asset('page/images/user_default.png') }}"
                                    alt="{{ $user->name }}">
                            </span>
                            {{ the_excerpt($user->name, 12) }}
                        </a>
                    </li>
                    <li class="nav-item nav-auth-item">
                        <form method="POST" action="{{ route('page.user.logout') }}"
                            onsubmit="return confirm('Bạn có chắc muốn đăng xuất?')" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-nav-logout" title="Đăng xuất">
                                <i class="fa fa-sign-out logout-icon"></i>
                                Đăng xuất
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item nav-auth-item">
                        <a href="{{ route('page.user.account') }}" class="btn-nav-login">
                            <i class="fa fa-sign-in"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item nav-auth-item">
                        <a href="{{ route('user.register') }}" class="btn-nav-reg">
                            <i class="fa fa-user-plus"></i> Đăng ký
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<script>
    (function () {
        var nav = document.getElementById('ftco-navbar');
        var navDropdowns = nav ? nav.querySelectorAll('.nav-main-links > .dropdown') : [];
        var closeTimers = new WeakMap();

        function isDesktopNav() {
            return window.matchMedia('(min-width: 992px)').matches;
        }

        function openMenu(dropdown) {
            if (!dropdown || !isDesktopNav()) return;
            clearTimeout(closeTimers.get(dropdown));

            var toggle = dropdown.querySelector('[data-toggle="dropdown"]');
            var menu = dropdown.querySelector('.dropdown-menu');
            dropdown.classList.add('show');
            if (toggle) {
                toggle.classList.add('show');
                toggle.setAttribute('aria-expanded', 'true');
            }
            if (menu) {
                menu.classList.add('show');
                menu.style.display = 'block';
            }
        }

        function closeMenu(dropdown) {
            if (!dropdown || !isDesktopNav()) return;

            var toggle = dropdown.querySelector('[data-toggle="dropdown"]');
            var menu = dropdown.querySelector('.dropdown-menu');
            dropdown.classList.remove('show');
            if (toggle) {
                toggle.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
            }
            if (menu) {
                menu.classList.remove('show');
                menu.style.display = '';
            }
        }

        function scheduleCloseMenu(dropdown) {
            clearTimeout(closeTimers.get(dropdown));
            closeTimers.set(dropdown, setTimeout(function () {
                closeMenu(dropdown);
            }, 180));
        }

        navDropdowns.forEach(function (dropdown) {
            var toggle = dropdown.querySelector('[data-toggle="dropdown"]');

            if (toggle && toggle.getAttribute('href') && toggle.getAttribute('href') !== '#') {
                toggle.addEventListener('click', function (event) {
                    if (isDesktopNav()) {
                        event.preventDefault();
                        window.location.href = toggle.href;
                    }
                });
            }

            dropdown.querySelectorAll('.nav-service-menu .dropdown-item').forEach(function (item) {
                item.addEventListener('click', function () {
                    window.location.href = item.href;
                });
            });
            dropdown.addEventListener('mouseenter', function () {
                openMenu(dropdown);
            });
            dropdown.addEventListener('mouseleave', function () {
                scheduleCloseMenu(dropdown);
            });
        });

        window.addEventListener('resize', function () {
            navDropdowns.forEach(closeMenu);
        });
    })();
</script>
