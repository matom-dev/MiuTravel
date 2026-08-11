@extends('page.layouts.page')
@section('title', 'Giới thiệu & Liên hệ | Miu Travel')
@section('style')
<style>
    /* ═══════════════════════════════════════════════
   GIỚI THIỆU — PREMIUM LIGHT DESIGN
═══════════════════════════════════════════════ */
    .about-wrap {
        font-family: 'Inter', sans-serif;
    }

    .about-wrap .container {
        width: min(100% - 28px, 1320px);
        max-width: 1320px;
    }

    .contact-form-header {
        margin-bottom: 28px;
    }

    .contact-form-header .sub {
        display: inline-block;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--primary, #f15d30);
        margin-bottom: 8px;
    }

    .contact-form-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.15rem, 2.8vw, 2.8rem);
        font-weight: 800;
        color: #1a202c;
        margin: 0 0 10px;
        line-height: 1.3;
    }

    .contact-form-header p {
        font-size: 17px;
        color: #64748b;
        margin: 0;
        line-height: 1.6;
    }

    .cf-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .cf-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 22px;
    }

    .cf-group label {
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #475569;
        margin-bottom: 9px;
    }

    .cf-input-wrap {
        position: relative;
    }

    .cf-input-wrap .cf-icon {
        position: absolute;
        left: 17px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 17px;
        pointer-events: none;
    }

    .cf-group--textarea .cf-icon {
        top: 16px;
        transform: none;
    }

    .cf-input-wrap input,
    .cf-input-wrap select,
    .cf-input-wrap textarea {
        width: 100%;
        min-height: 58px;
        padding: 15px 17px 15px 50px;
        border: 2px solid #e8edf2;
        border-radius: 12px;
        font-size: 18px;
        color: #1a202c;
        background: #f8fafc;
        outline: none;
        transition: border-color .25s, box-shadow .25s, background .25s;
        font-family: inherit;
        appearance: none;
    }

    .cf-input-wrap input:focus,
    .cf-input-wrap select:focus,
    .cf-input-wrap textarea:focus {
        border-color: var(--primary, #f15d30);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(241, 93, 48, .08);
    }

    .cf-input-wrap input::placeholder,
    .cf-input-wrap textarea::placeholder {
        color: #b0bec5;
    }

    .cf-input-wrap textarea {
        resize: vertical;
        min-height: 156px;
        padding-top: 14px;
        line-height: 1.6;
    }

    .cf-input-wrap select {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2394a3b8' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
    }

    .cf-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 62px;
        padding: 18px 22px;
        background: linear-gradient(135deg, var(--primary, #f15d30), var(--primary-dark, #d94a1e));
        color: #fff;
        border: none;
        border-radius: 14px;
        font-size: 19px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 6px 24px rgba(241, 93, 48, .35);
        transition: transform .2s, box-shadow .2s;
        font-family: inherit;
        margin-top: 6px;
    }

    .cf-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(241, 93, 48, .45);
    }

    .cf-guarantee {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 18px;
        flex-wrap: wrap;
    }

    .cf-guarantee-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }

    .cf-guarantee-item .fa {
        color: #22c55e;
        font-size: 13px;
    }

    .cf-alert {
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cf-alert--success {
        background: rgba(34, 197, 94, .08);
        border: 1.5px solid rgba(34, 197, 94, .3);
        color: #16a34a;
    }

    .cf-alert--error {
        background: rgba(239, 68, 68, .08);
        border: 1.5px solid rgba(239, 68, 68, .3);
        color: #dc2626;
    }

    @media (max-width: 575px) {
        .cf-row {
            grid-template-columns: 1fr;
        }

        .cf-input-wrap input,
        .cf-input-wrap select,
        .cf-input-wrap textarea {
            font-size: 16px;
        }
    }

    /* Company profile refresh */
    .company-about {
        --miu-ink: #102032;
        --miu-muted: #66758a;
        --miu-line: #e8edf2;
        --miu-soft: #f5f8fb;
        --miu-teal: #0f766e;
        --miu-orange: #f15d30;
        background: var(--miu-soft);
        color: var(--miu-ink);
    }

    .company-about .container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .miu-page-banner {
        background: var(--miu-soft);
        padding: 18px 0 16px;
    }

    .miu-page-banner .breadcrumbs {
        color: var(--miu-muted);
        font-size: 17px;
        font-weight: 800;
        margin: 0 0 10px;
    }

    .miu-page-banner .breadcrumbs a,
    .miu-page-banner .breadcrumbs span {
        color: var(--miu-muted);
        text-decoration: none;
    }

    .miu-page-banner .breadcrumbs a:hover {
        color: var(--miu-orange);
    }

    .miu-page-banner-title {
        color: var(--miu-ink);
        font-size: clamp(2.45rem, 4.6vw, 4rem);
        font-weight: 900;
        letter-spacing: 0;
        line-height: 1.06;
        margin: 0;
    }

    .miu-section {
        padding: 36px 0;
        background: var(--miu-soft);
    }

    .miu-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: var(--miu-teal);
        font-size: 13px;
        font-weight: 850;
        letter-spacing: 1.8px;
        text-transform: uppercase;
    }

    .miu-heading {
        margin: 0;
        color: var(--miu-ink);
        font-size: clamp(2rem, 3vw, 3.15rem);
        font-weight: 900;
        line-height: 1.05;
    }

    .miu-company-hero .miu-heading {
        font-size: clamp(2.8rem, 5vw, 5.4rem);
        letter-spacing: 0;
    }

    .miu-heading span,
    .miu-copy strong {
        color: var(--miu-orange);
    }

    .miu-copy {
        color: var(--miu-muted);
        font-size: 17px;
        line-height: 1.8;
    }

    .miu-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        margin-top: 28px;
        max-width: 720px;
    }

    .miu-rating-image {
        display: block;
        width: min(100%, 380px);
        height: auto;
        margin: 26px auto 0;
        background: transparent;
        box-shadow: none;
    }

    .miu-hero-stats {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0;
        margin-top: 18px;
        max-width: 720px;
        color: var(--miu-muted);
    }

    .miu-hero-stat {
        display: inline-flex;
        align-items: baseline;
        justify-content: center;
        gap: 7px;
        padding: 0 18px;
        border-right: 1px solid var(--miu-line);
        text-align: center;
    }

    .miu-hero-stat:first-child {
        padding-left: 0;
    }

    .miu-hero-stat:last-child {
        padding-right: 0;
        border-right: 0;
    }

    .miu-hero-stat b {
        color: var(--miu-ink);
        font-size: 24px;
        font-weight: 900;
        line-height: 1;
    }

    .miu-hero-stat span {
        color: var(--miu-muted);
        font-size: 13px;
        font-weight: 750;
        line-height: 1.35;
    }

    .miu-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 48px;
        padding: 0 22px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 850;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .miu-btn:hover {
        text-decoration: none;
        transform: translateY(-2px);
    }

    .miu-btn-primary {
        color: #ffffff;
        background: var(--miu-orange);
        box-shadow: 0 16px 34px rgba(241, 93, 48, .28);
    }

    .miu-btn-primary:hover {
        color: #ffffff;
        box-shadow: 0 20px 42px rgba(241, 93, 48, .36);
    }

    .miu-btn-ghost {
        color: var(--miu-ink);
        background: #ffffff;
        border: 1px solid var(--miu-line);
    }

    .miu-company-hero {
        position: relative;
        overflow: hidden;
        padding: 34px 0 38px;
        background: var(--miu-soft);
    }

    .miu-company-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.08fr) minmax(420px, .92fr);
        gap: 52px;
        align-items: center;
    }

    .miu-company-hero p.miu-copy {
        max-width: 720px;
        margin: 16px 0 0;
        font-size: 18px;
    }

    .miu-hero-media {
        position: relative;
        min-height: 505px;
        padding: 0;
        transform: translateX(34px);
    }

    .miu-hero-photo {
        position: absolute;
        overflow: hidden;
        border-radius: 8px;
        background-size: cover;
        background-position: center;
        box-shadow: 0 22px 46px rgba(15, 23, 42, .13);
    }

    .miu-hero-photo-main {
        inset: 0 0 28px 0;
        background-image: url("{{ asset('page/images/introduce_1.jpg') }}");
    }

    .miu-hero-note {
        position: absolute;
        left: auto;
        right: 20px;
        bottom: -58px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 172px;
        height: 172px;
        padding: 18px;
        border-radius: 8px;
        color: #ffffff;
        background: rgba(18, 63, 85, .86);
        box-shadow: 0 14px 30px rgba(18, 63, 85, .18);
        backdrop-filter: blur(8px);
    }

    .miu-hero-note b {
        display: block;
        margin-bottom: 8px;
        font-size: 17px;
        line-height: 1.25;
    }

    .miu-hero-note span {
        color: rgba(255, 255, 255, .78);
        font-size: 12px;
        line-height: 1.45;
    }

    .miu-story-grid {
        display: grid;
        grid-template-columns: minmax(360px, .9fr) minmax(0, 1.1fr);
        gap: 48px;
        align-items: center;
    }

    .miu-story-media {
        display: grid;
        grid-template-columns: 1fr;
        gap: 14px;
        align-content: start;
    }

    .miu-story-media .miu-story-img:first-child {
        grid-column: auto;
    }

    .miu-story-img {
        min-height: 310px;
        border-radius: 8px;
        background-size: cover;
        background-position: center;
        box-shadow: 0 14px 32px rgba(15, 23, 42, .07);
    }

    .miu-story .miu-heading {
        max-width: 760px;
        font-size: clamp(2rem, 2.8vw, 3rem);
        line-height: 1.12;
    }

    .miu-story-lead {
        margin: 18px 0 18px;
        color: var(--miu-ink);
        font-size: 19px;
        font-weight: 850;
        line-height: 1.55;
    }

    .miu-story .miu-copy {
        max-width: 760px;
        font-size: 16px;
        line-height: 1.78;
    }

    .miu-principles {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-top: 24px;
    }

    .miu-principle {
        display: grid;
        grid-template-columns: 38px minmax(0, 1fr);
        gap: 12px;
        align-items: start;
        padding: 16px;
        border: 1px solid var(--miu-line);
        border-radius: 8px;
        background: #ffffff;
    }

    .miu-principle i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 8px;
        color: var(--miu-orange);
        background: rgba(241, 93, 48, .09);
        font-size: 17px;
    }

    .miu-principle h3 {
        grid-column: 2;
        margin: 0 0 5px;
        color: var(--miu-ink);
        font-size: 16px;
        font-weight: 850;
    }

    .miu-principle p {
        grid-column: 2;
        margin: 0;
        color: var(--miu-muted);
        font-size: 13.5px;
        line-height: 1.62;
    }

    .miu-depth {
        background: var(--miu-soft);
    }

    .miu-depth-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.12fr) minmax(340px, .88fr);
        gap: 54px;
        align-items: center;
    }

    .miu-depth .miu-heading {
        max-width: 760px;
        font-size: clamp(2rem, 2.9vw, 3.1rem);
        line-height: 1.12;
    }

    .miu-depth-text {
        display: grid;
        gap: 16px;
        margin-top: 22px;
    }

    .miu-depth-text p {
        margin: 0;
    }

    .miu-depth-panel {
        justify-self: end;
        width: 100%;
        max-width: 690px;
        padding: 30px;
        border: 0;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 18px 44px rgba(15, 23, 42, .06);
        transform: translateX(24px);
    }

    .miu-depth-row {
        display: grid;
        grid-template-columns: 44px minmax(0, 1fr);
        gap: 16px;
        padding: 18px 0;
        border-bottom: 1px solid #edf1f5;
    }

    .miu-depth-row:first-child {
        padding-top: 0;
    }

    .miu-depth-row:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .miu-depth-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 8px;
        color: var(--miu-teal);
        background: rgba(15, 118, 110, .1);
        font-size: 18px;
    }

    .miu-depth-row h3 {
        margin: 0 0 6px;
        color: var(--miu-ink);
        font-size: 18px;
        font-weight: 900;
    }

    .miu-depth-row p {
        margin: 0;
        color: var(--miu-muted);
        font-size: 14.5px;
        line-height: 1.68;
    }

    .miu-service-section {
        background: var(--miu-soft);
    }

    .miu-service-section .miu-heading {
        white-space: nowrap;
        font-size: clamp(2rem, 3.2vw, 3.35rem);
    }

    .miu-service-section .miu-section-head {
        display: block;
    }

    .miu-service-section .miu-section-head>div {
        width: 100%;
    }

    .miu-service-section .miu-section-head .miu-copy {
        max-width: 980px;
        margin-top: 16px;
    }

    .miu-section-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 32px;
        margin-bottom: 18px;
    }

    .miu-section-head .miu-copy {
        max-width: 460px;
        margin: 0;
    }

    .miu-service-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 34px 48px;
    }

    .miu-service-item {
        position: relative;
        display: grid;
        grid-template-columns: minmax(220px, .92fr) minmax(0, 1fr);
        gap: 24px;
        align-items: center;
        min-height: 220px;
        overflow: visible;
        border-radius: 8px;
        background: transparent;
        border: 0;
        box-shadow: none;
        cursor: default;
    }

    .miu-service-item::before {
        display: none;
    }

    .miu-service-photo {
        height: 220px;
        min-height: 220px;
        border-radius: 18px;
        background-size: cover;
        background-position: center;
    }

    .miu-service-body {
        position: static;
        padding: 0;
        color: var(--miu-ink);
    }

    .miu-service-body h3 {
        margin: 0 0 16px;
        color: var(--miu-ink);
        font-size: 25px;
        font-weight: 900;
        line-height: 1.25;
    }

    .miu-service-body p {
        margin: 0 0 8px;
        color: var(--miu-muted);
        font-size: 15.5px;
        line-height: 1.65;
    }

    .miu-service-body p:last-child {
        margin-bottom: 0;
    }

    .miu-process {
        background: var(--miu-soft);
    }

    .miu-process .miu-heading {
        font-size: clamp(2rem, 2.9vw, 3.1rem);
        line-height: 1.12;
    }

    .miu-process .miu-copy {
        max-width: 720px;
        margin-top: 16px;
    }

    .miu-process-grid {
        position: relative;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
        margin-top: 22px;
        overflow: hidden;
        border: 0;
        border-radius: 0;
    }

    .miu-process-grid::before {
        content: '';
        position: absolute;
        left: 8%;
        right: 8%;
        top: 31px;
        height: 2px;
        background: #dbe8e6;
    }

    .miu-step {
        position: relative;
        min-height: 190px;
        padding: 0 10px;
        border-right: 0;
        background: transparent;
    }

    .miu-step-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin-bottom: 22px;
        border: 8px solid #ffffff;
        border-radius: 50%;
        color: #ffffff;
        background: var(--miu-teal);
        font-weight: 900;
        box-shadow: 0 14px 28px rgba(15, 118, 110, .18);
    }

    .miu-step h3 {
        margin: 0 0 9px;
        color: var(--miu-ink);
        font-size: 20px;
        font-weight: 900;
    }

    .miu-step p {
        margin: 0;
        color: var(--miu-muted);
        font-size: 14.5px;
        line-height: 1.72;
    }

    .miu-commitment {
        overflow: hidden;
        background: var(--miu-soft);
        color: var(--miu-ink);
    }

    .miu-commitment-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.18fr) minmax(320px, .82fr);
        gap: 44px;
        align-items: center;
    }

    .miu-commitment .miu-eyebrow,
    .miu-commitment .miu-heading,
    .miu-commitment .miu-copy {
        color: var(--miu-ink);
    }

    .miu-commitment .miu-copy {
        color: var(--miu-muted);
    }

    .miu-commitment .miu-heading+.miu-copy {
        margin-top: 16px;
    }

    .miu-commitment .miu-copy+.miu-copy {
        margin-top: 8px;
    }

    .miu-commitment .miu-heading {
        font-size: clamp(2rem, 2.72vw, 2.95rem);
        line-height: 1.12;
    }

    .miu-commitment .miu-title-line {
        display: block;
        color: inherit;
    }

    @media (min-width: 1200px) {
        .miu-commitment .miu-title-line {
            white-space: nowrap;
        }
    }

    .miu-commitment-list {
        display: grid;
        gap: 14px;
        margin-top: 26px;
    }

    .miu-commitment-item {
        display: grid;
        grid-template-columns: 42px minmax(0, 1fr);
        gap: 14px;
        align-items: start;
    }

    .miu-commitment-item i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        color: #ffffff;
        background: var(--miu-orange);
    }

    .miu-commitment-item h3 {
        margin: 0 0 4px;
        color: var(--miu-ink);
        font-size: 18px;
        font-weight: 850;
    }

    .miu-commitment-item p {
        margin: 0;
        color: var(--miu-muted);
        font-size: 14px;
        line-height: 1.65;
    }

    .miu-commitment-photo {
        min-height: 500px;
        border-radius: 8px;
        background-image: url("{{ asset('page/images/introduce_4.jpg') }}");
        background-size: cover;
        background-position: center;
        box-shadow: 0 18px 48px rgba(15, 23, 42, .1);
    }

    .miu-contact {
        background: var(--miu-soft);
    }

    .miu-contact .miu-section-head {
        display: block;
        margin-bottom: 20px;
    }

    .miu-contact .miu-heading {
        max-width: 720px;
        font-size: clamp(2rem, 2.65vw, 2.85rem);
        line-height: 1.14;
    }

    .miu-contact .miu-copy {
        max-width: 900px;
        margin-top: 14px;
        font-size: 15.5px;
        line-height: 1.65;
    }

    .miu-contact-grid {
        display: grid;
        grid-template-columns: minmax(310px, .7fr) minmax(0, 1fr);
        gap: 24px;
        align-items: stretch;
    }

    .miu-contact-panel,
    .miu-form-panel {
        height: 100%;
        border: 1px solid rgba(226, 232, 240, .9);
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 18px 48px rgba(15, 23, 42, .07);
    }

    .miu-contact-panel {
        padding: 22px 24px;
    }

    .miu-contact-intro {
        margin-bottom: 6px;
        padding: 16px;
        border: 1px solid rgba(18, 128, 116, .16);
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(18, 128, 116, .08), rgba(241, 93, 48, .05));
    }

    .miu-contact-intro span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--miu-teal);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .miu-contact-intro strong {
        display: block;
        margin-top: 7px;
        color: var(--miu-ink);
        font-size: 18px;
        font-weight: 900;
        line-height: 1.25;
    }

    .miu-contact-intro p {
        margin: 8px 0 0;
        color: var(--miu-muted);
        font-size: 13.5px;
        line-height: 1.55;
    }

    .miu-contact-line {
        display: grid;
        grid-template-columns: 34px minmax(0, 1fr);
        gap: 12px;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--miu-line);
    }

    .miu-contact-line:last-child {
        border-bottom: 0;
    }

    .miu-contact-line i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        color: var(--miu-orange);
        background: rgba(241, 93, 48, .1);
        font-size: 14px;
    }

    .miu-contact-line span {
        display: block;
        color: #8a96a6;
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .miu-contact-line a,
    .miu-contact-line p {
        margin: 3px 0 0;
        color: var(--miu-ink);
        font-size: 14.5px;
        font-weight: 850;
        text-decoration: none;
    }

    .miu-form-panel {
        padding: 18px 20px;
    }

    .miu-form-panel .contact-form-header {
        margin-bottom: 14px;
    }

    .miu-form-panel .contact-form-header h2 {
        margin: 0 0 6px;
        color: var(--miu-ink);
        font-size: 21px;
        font-weight: 900;
        line-height: 1.25;
    }

    .miu-form-panel .contact-form-header p {
        margin: 0;
        color: var(--miu-muted);
        font-size: 13.5px;
        line-height: 1.5;
    }

    .miu-form-panel .cf-row {
        gap: 14px;
    }

    .miu-form-panel .cf-group {
        margin-bottom: 10px;
    }

    .miu-form-panel .cf-group label {
        font-size: 11px;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .miu-form-panel .cf-input-wrap input,
    .miu-form-panel .cf-input-wrap select,
    .miu-form-panel .cf-input-wrap textarea {
        min-height: 42px;
        padding-top: 10px;
        padding-bottom: 10px;
        border-width: 1px;
        border-color: #e1e8f0;
        background: #fbfdff;
        font-size: 14px;
        border-radius: 10px;
    }

    .miu-form-panel .cf-input-wrap .cf-icon {
        left: 15px;
        font-size: 14px;
    }

    .miu-form-panel .cf-input-wrap textarea {
        min-height: 86px;
    }

    .miu-form-panel .cf-guarantee {
        justify-content: flex-start;
        gap: 10px;
        margin-top: 10px;
    }

    .miu-form-panel .cf-guarantee-item {
        font-size: 12px;
    }

    .miu-form-panel .cf-submit {
        border-radius: 8px;
        min-height: 46px;
        padding: 12px 16px;
        font-size: 15px;
        margin-top: 0;
    }

    @media (max-width: 1199px) {

        .miu-company-hero-grid,
        .miu-story-grid,
        .miu-depth-grid,
        .miu-commitment-grid,
        .miu-contact-grid {
            grid-template-columns: 1fr;
        }

        .miu-contact .miu-copy {
            margin-left: 0;
        }

        .miu-depth-panel {
            max-width: none;
            transform: none;
        }

        .miu-hero-media {
            min-height: 440px;
            transform: none;
        }

        .miu-service-grid,
        .miu-process-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .miu-step:nth-child(2) {
            border-right: 0;
        }

        .miu-step {
            border-bottom: 1px solid var(--miu-line);
        }

        .miu-process-grid::before {
            display: none;
        }

        .miu-step {
            display: grid;
            grid-template-columns: 64px minmax(0, 1fr);
            gap: 16px;
            min-height: auto;
            padding: 22px 0;
        }

        .miu-step-num {
            margin-bottom: 0;
        }
    }

    @media (max-width: 767px) {
        .miu-section {
            padding: 28px 0;
        }

        .miu-company-hero {
            padding: 28px 0 32px;
        }

        .miu-depth {
            background: var(--miu-soft);
        }

        .miu-company-hero-grid {
            gap: 34px;
        }

        .miu-hero-stats {
            align-items: flex-start;
            gap: 10px 0;
        }

        .miu-hero-stat {
            width: 100%;
            padding: 0;
            border-right: 0;
        }

        .miu-heading {
            font-size: 2.35rem;
        }

        .miu-principles,
        .miu-service-grid,
        .miu-process-grid {
            grid-template-columns: 1fr;
        }

        .miu-service-item {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .miu-service-photo {
            height: 180px;
            min-height: 180px;
            border-radius: 18px;
        }

        .miu-service-body {
            padding: 18px 0 0;
        }

        .miu-service-section .miu-heading {
            white-space: normal;
        }

        .miu-story-img {
            min-height: 220px;
        }

        .miu-hero-media {
            min-height: 360px;
            padding: 0;
            transform: none;
        }

        .miu-hero-photo-main {
            inset: 0;
        }

        .miu-hero-note {
            right: 16px;
            bottom: -44px;
            width: 132px;
            height: 132px;
            padding: 12px 14px;
        }

        .miu-hero-note span {
            display: none;
        }

        .miu-section-head {
            display: block;
        }

        .miu-section-head .miu-copy {
            margin-top: 16px;
        }

        .miu-step,
        .miu-step:nth-child(2) {
            border-right: 0;
        }

        .miu-commitment-photo {
            min-height: 320px;
        }

        .miu-form-panel,
        .miu-contact-panel,
        .miu-depth-panel {
            padding: 24px;
        }

        .miu-depth-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }
</style>
@stop
@section('seo')
<meta name="description"
    content="Giới thiệu Miu Travel và liên hệ tư vấn tour, khách sạn, thuê xe. Chúng tôi luôn sẵn sàng hỗ trợ bạn.">
@stop

@section('content')
<div class="about-wrap company-about">
    <section class="miu-page-banner">
        <div class="container">
            <p class="breadcrumbs">
                <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i
                            class="fa fa-chevron-right"></i></a></span>
                <span>Giới thiệu</span>
            </p>
            <h1 class="miu-page-banner-title">Công ty du lịch địa phương</h1>
        </div>
    </section>

    <section class="miu-company-hero">
        <div class="container">
            <div class="miu-company-hero-grid">
                <div>
                    <h1 class="miu-heading">Miu Travel</h1>
                    <p class="miu-copy">
                        Miu Travel đồng hành cùng du khách khám phá Quảng Bình, Quảng Trị và miền Trung bằng những
                        lịch trình rõ ràng, dịch vụ được chọn lọc và đội ngũ tư vấn am hiểu địa phương. Chúng tôi kết
                        nối tour tham quan, khách sạn, thuê xe và kinh nghiệm điểm đến thành một hành trình liền mạch,
                        giúp khách dễ chuẩn bị, dễ lựa chọn và yên tâm hơn trước mỗi chuyến đi.
                    </p>

                    <div class="miu-actions">
                        <a href="{{ route('tour') }}" class="miu-btn miu-btn-primary">
                            <i class="fa fa-compass"></i> Xem tour đang mở
                        </a>
                        <a href="#lien-he" class="miu-btn miu-btn-ghost">
                            <i class="fa fa-phone"></i> Liên hệ Miu Travel
                        </a>
                    </div>

                    <img src="{{ asset('page/images/rating-5-sao.png') }}" alt="Đánh giá 5 sao từ khách hàng"
                        class="miu-rating-image">

                    <div class="miu-hero-stats" aria-label="Số liệu nổi bật của Miu Travel">
                        <div class="miu-hero-stat">
                            <b>10+</b>
                            <span>Năm kinh nghiệm</span>
                        </div>
                        <div class="miu-hero-stat">
                            <b>500+</b>
                            <span>Tour đã thực hiện</span>
                        </div>
                        <div class="miu-hero-stat">
                            <b>24/7</b>
                            <span>Hỗ trợ du khách</span>
                        </div>
                    </div>

                </div>

                <div class="miu-hero-media" aria-hidden="true">
                    <div class="miu-hero-photo miu-hero-photo-main"></div>
                    <div class="miu-hero-note">
                        <b>Đi để khám phá</b>
                        <span>Mỗi chuyến đi được chuẩn bị kỹ từ lịch trình, lưu trú, di chuyển đến trải nghiệm tại điểm
                            đến.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="miu-section miu-story">
        <div class="container">
            <div class="miu-story-grid">
                <div class="miu-story-media">
                    <div class="miu-story-img"
                        style="background-image: url({{ asset('page/images/introduce_2.jpg') }});">
                    </div>
                    <div class="miu-story-img"
                        style="background-image: url({{ asset('page/images/introduce_3.jpg') }});">
                    </div>
                </div>

                <div>
                    <span class="miu-eyebrow"><i class="fa fa-leaf"></i> Câu chuyện Miu Travel</span>
                    <h2 class="miu-heading">Từ hiểu địa phương đến tạo nên hành trình đáng nhớ</h2>
                    <p class="miu-story-lead">
                        Miu Travel không chỉ bán tour, chúng tôi giúp khách chọn đúng trải nghiệm, đúng thời điểm và
                        đúng nhu cầu của từng nhóm đi.
                    </p>
                    <p class="miu-copy">
                        Với lợi thế am hiểu điểm đến miền Trung, Miu Travel tập trung xây dựng các hành trình thực tế:
                        lịch trình dễ theo dõi, chi phí minh bạch, dịch vụ lưu trú và vận chuyển được kiểm tra kỹ trước
                        khi giới thiệu đến khách hàng.
                    </p>

                    <div class="miu-principles">
                        <div class="miu-principle">
                            <i class="fa fa-map-signs"></i>
                            <h3>Rõ lịch trình</h3>
                            <p>Thông tin điểm đến, thời gian, dịch vụ và chi phí được trình bày dễ hiểu trước khi đặt.
                            </p>
                        </div>
                        <div class="miu-principle">
                            <i class="fa fa-handshake-o"></i>
                            <h3>Đồng hành thật</h3>
                            <p>Đội ngũ tư vấn hỗ trợ trước, trong và sau chuyến đi để khách luôn yên tâm.</p>
                        </div>
                        <div class="miu-principle">
                            <i class="fa fa-shield"></i>
                            <h3>An toàn ưu tiên</h3>
                            <p>Chúng tôi chú trọng hướng dẫn, bảo hiểm và lựa chọn đối tác vận hành phù hợp.</p>
                        </div>
                        <div class="miu-principle">
                            <i class="fa fa-heart"></i>
                            <h3>Trải nghiệm có tâm</h3>
                            <p>Mỗi lịch trình được cân bằng giữa tham quan, nghỉ ngơi và trải nghiệm văn hóa địa phương.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="miu-section miu-depth">
        <div class="container">
            <div class="miu-depth-grid">
                <div>
                    <span class="miu-eyebrow"><i class="fa fa-compass"></i> Cách Miu Travel xây dựng hành trình</span>
                    <h2 class="miu-heading">Mỗi chuyến đi bắt đầu từ sự hiểu đúng về khách hàng</h2>
                    <div class="miu-depth-text">
                        <p class="miu-copy">
                            Một lịch trình đẹp không chỉ là ghép nhiều điểm đến vào cùng một ngày. Với Miu Travel, hành
                            trình cần có nhịp đi hợp lý, thời gian nghỉ đủ, điểm ăn uống phù hợp và những khoảng dừng để
                            du khách thật sự cảm nhận được nơi mình đang đến.
                        </p>
                        <p class="miu-copy">
                            Chúng tôi ưu tiên tư vấn theo nhu cầu thực tế: nhóm gia đình cần sự thoải mái, nhóm bạn trẻ
                            thích trải nghiệm nhiều hoạt động, khách đoàn cần sự đúng giờ và rõ ràng, còn khách tự túc
                            thường cần những gợi ý linh hoạt để chủ động hơn trong chuyến đi.
                        </p>
                        <p class="miu-copy">
                            Vì vậy, Miu Travel luôn xem khâu tư vấn là một phần quan trọng của dịch vụ. Trước khi khách
                            đặt tour, đội ngũ sẽ trao đổi kỹ về thời gian, ngân sách, phương tiện, lưu trú và mong muốn
                            riêng để đưa ra phương án phù hợp, dễ hiểu và dễ quyết định.
                        </p>
                        <p class="miu-copy">
                            Sau mỗi hành trình, chúng tôi tiếp tục ghi nhận phản hồi để điều chỉnh điểm dừng, thời gian
                            tham quan và chất lượng đối tác. Nhờ vậy, các sản phẩm của Miu Travel luôn được cập nhật
                            gần với trải nghiệm thực tế thay vì chỉ dừng lại ở thông tin giới thiệu.
                        </p>
                    </div>
                </div>

                <div class="miu-depth-panel">
                    <div class="miu-depth-row">
                        <div class="miu-depth-icon"><i class="fa fa-calendar-plus-o"></i></div>
                        <div>
                            <h3>Mô hình tour linh hoạt</h3>
                            <p>
                                Admin tạo chương trình tour và thời gian rõ ràng. Khách tự chọn ngày đi, hệ thống tự
                                tính ngày về dự kiến, Miu Travel liên hệ xác nhận lại trước khi chốt booking.
                            </p>
                        </div>
                    </div>
                    <div class="miu-depth-row">
                        <div class="miu-depth-icon"><i class="fa fa-map-marker"></i></div>
                        <div>
                            <h3>Am hiểu tuyến điểm miền Trung</h3>
                            <p>
                                Từ hang động, biển, di tích lịch sử đến đặc sản địa phương, chúng tôi chọn điểm đến theo
                                mùa, thời tiết và khả năng di chuyển thực tế của từng nhóm khách.
                            </p>
                        </div>
                    </div>
                    <div class="miu-depth-row">
                        <div class="miu-depth-icon"><i class="fa fa-calendar-check-o"></i></div>
                        <div>
                            <h3>Lịch trình có nhịp đi rõ ràng</h3>
                            <p>
                                Miu Travel cân đối thời gian tham quan, nghỉ ngơi, ăn uống và di chuyển để hành trình
                                không bị quá gấp, đặc biệt với gia đình có trẻ em hoặc người lớn tuổi.
                            </p>
                        </div>
                    </div>
                    <div class="miu-depth-row">
                        <div class="miu-depth-icon"><i class="fa fa-users"></i></div>
                        <div>
                            <h3>Phù hợp từng kiểu khách</h3>
                            <p>
                                Mỗi nhóm khách có một cách đi khác nhau. Chúng tôi tư vấn linh hoạt cho khách lẻ, nhóm
                                bạn, gia đình, doanh nghiệp và đoàn trường học.
                            </p>
                        </div>
                    </div>
                    <div class="miu-depth-row">
                        <div class="miu-depth-icon"><i class="fa fa-comments-o"></i></div>
                        <div>
                            <h3>Trao đổi rõ trước khi đặt</h3>
                            <p>
                                Các thông tin về dịch vụ, chi phí, điểm đón, ngày khởi hành mong muốn và những lưu ý
                                quan trọng
                                được gửi trước để khách chủ động chuẩn bị.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="miu-section miu-service-section">
        <div class="container">
            <div class="miu-section-head">
                <div>
                    <span class="miu-eyebrow"><i class="fa fa-briefcase"></i> Dịch vụ của chúng tôi</span>
                    <h2 class="miu-heading">Một điểm chạm cho chuyến đi trọn vẹn</h2>
                    <p class="miu-copy">
                        Miu Travel kết nối các nhu cầu chính của chuyến đi, từ tour tham quan đến lưu trú, thuê xe và tư
                        vấn trải nghiệm địa phương.<br>
                        Mỗi dịch vụ được sắp xếp để khách dễ chọn, dễ đặt và nhận được hỗ trợ xuyên suốt hành trình.
                    </p>
                </div>
            </div>

            <div class="miu-service-grid">
                <div class="miu-service-item">
                    <div class="miu-service-photo"
                        style="background-image: url({{ asset('page/images/services-1.jpg') }});"></div>
                    <div class="miu-service-body">
                        <h3>Khám phá và trải nghiệm</h3>
                        <p>
                            Miu Travel thiết kế tour theo nhu cầu từng nhóm khách: gia đình, khách lẻ, nhóm bạn hoặc
                            đoàn
                            doanh nghiệp. Lịch trình được tư vấn rõ điểm đến, thời gian, chi phí, dịch vụ đi kèm và
                            những
                            lưu ý cần chuẩn bị trước khi khởi hành.
                        </p>
                    </div>
                </div>
                <div class="miu-service-item">
                    <div class="miu-service-photo"
                        style="background-image: url({{ asset('page/images/services-4.jpg') }});"></div>
                    <div class="miu-service-body">
                        <h3>Trải nghiệm địa phương</h3>
                        <p>
                            Ngoài tour chính, Miu Travel tư vấn thêm đặc sản, điểm check-in, thời điểm nên đi và kinh
                            nghiệm
                            thực tế tại địa phương. Những gợi ý này giúp chuyến đi gần gũi hơn, có nhiều khoảnh khắc
                            đáng
                            nhớ hơn.
                        </p>
                    </div>
                </div>
                <div class="miu-service-item">
                    <div class="miu-service-photo"
                        style="background-image: url({{ asset('page/images/services-2.jpg') }});"></div>
                    <div class="miu-service-body">
                        <h3>Khách sạn</h3>
                        <p>
                            Chúng tôi gợi ý nơi lưu trú dựa trên vị trí, phong cách chuyến đi và nhu cầu thực
                            tế
                            của khách. Miu Travel ưu tiên những khách sạn thuận tiện di chuyển, thông tin liên hệ rõ
                            ràng
                            và
                            phù hợp lịch trình.
                        </p>
                    </div>
                </div>
                <div class="miu-service-item">
                    <div class="miu-service-photo"
                        style="background-image: url({{ asset('page/images/services-3.jpg') }});"></div>
                    <div class="miu-service-body">
                        <h3>Thuê xe</h3>
                        <p>
                            Dịch vụ thuê xe được kết nối theo số lượng khách, cung đường và thời gian sử dụng. Từ xe gia
                            đình đến xe đoàn, Miu Travel hỗ trợ chọn phương tiện phù hợp để hành trình chủ động và thoải
                            mái hơn.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="miu-section miu-process">
        <div class="container">
            <span class="miu-eyebrow"><i class="fa fa-check-square-o"></i> Quy trình đồng hành</span>
            <h2 class="miu-heading">Làm rõ từ đầu, hỗ trợ đến cuối</h2>
            <p class="miu-copy">
                Miu Travel giữ quy trình tư vấn gọn và minh bạch để khách dễ theo dõi từng bước, từ lúc trao đổi nhu cầu
                đến khi hoàn tất chuyến đi.
            </p>

            <div class="miu-process-grid">
                <div class="miu-step">
                    <div class="miu-step-num">01</div>
                    <div>
                        <h3>Lắng nghe nhu cầu</h3>
                        <p>Tiếp nhận thời gian, số lượng khách, ngân sách và phong cách chuyến đi mong muốn. Từ đó, Miu
                            Travel hiểu rõ bạn cần một lịch trình nghỉ dưỡng, khám phá hay kết hợp nhiều trải nghiệm.
                        </p>
                    </div>
                </div>
                <div class="miu-step">
                    <div class="miu-step-num">02</div>
                    <div>
                        <h3>Đề xuất phương án</h3>
                        <p>Gợi ý tour, khách sạn, xe và lịch trình phù hợp để khách dễ so sánh. Mỗi phương án đều được
                            trình bày rõ điểm mạnh, chi phí dự kiến và mức độ phù hợp với nhóm đi.</p>
                    </div>
                </div>
                <div class="miu-step">
                    <div class="miu-step-num">03</div>
                    <div>
                        <h3>Xác nhận dịch vụ</h3>
                        <p>Chốt thông tin đặt dịch vụ, lịch trình và các lưu ý trước khi khởi hành. Đội ngũ sẽ kiểm tra
                            lại thời gian, điểm đón, lưu trú và các hạng mục đã thống nhất.</p>
                    </div>
                </div>
                <div class="miu-step">
                    <div class="miu-step-num">04</div>
                    <div>
                        <h3>Hỗ trợ hành trình</h3>
                        <p>Luôn sẵn sàng hỗ trợ khi khách cần thay đổi, hỏi thông tin hoặc xử lý tình huống. Sau chuyến
                            đi, Miu Travel ghi nhận phản hồi để cải thiện dịch vụ cho những hành trình tiếp theo.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="miu-section miu-commitment">
        <div class="container">
            <div class="miu-commitment-grid">
                <div>
                    <span class="miu-eyebrow"><i class="fa fa-diamond"></i> Cam kết của Miu Travel</span>
                    <h2 class="miu-heading">
                        <span class="miu-title-line">Chuyên nghiệp trong cách làm,</span>
                        <span class="miu-title-line">gần gũi trong cách đồng hành</span>
                    </h2>
                    <p class="miu-copy">
                        Miu Travel chú trọng từng chi tiết nhỏ để mỗi chuyến đi diễn ra nhẹ nhàng, rõ ràng và đúng như
                        mong đợi của khách hàng.
                    </p>
                    <p class="miu-copy">
                        Điều quan trọng nhất với chúng tôi là sự an tâm của khách hàng: đặt dịch vụ dễ hiểu, đi đúng
                        lịch trình và nhận được hỗ trợ khi cần.
                    </p>

                    <div class="miu-commitment-list">
                        <div class="miu-commitment-item">
                            <i class="fa fa-check"></i>
                            <div>
                                <h3>Minh bạch thông tin</h3>
                                <p>Chi phí, dịch vụ bao gồm và các điều kiện đặt tour được trao đổi rõ ràng.</p>
                            </div>
                        </div>
                        <div class="miu-commitment-item">
                            <i class="fa fa-check"></i>
                            <div>
                                <h3>Chọn lọc đối tác</h3>
                                <p>Lưu trú, xe và điểm trải nghiệm được cân nhắc theo chất lượng thực tế.</p>
                            </div>
                        </div>
                        <div class="miu-commitment-item">
                            <i class="fa fa-check"></i>
                            <div>
                                <h3>Tư vấn đúng nhu cầu</h3>
                                <p>Không ép lựa chọn đắt nhất; ưu tiên phương án hợp lý với từng nhóm khách.</p>
                            </div>
                        </div>
                        <div class="miu-commitment-item">
                            <i class="fa fa-check"></i>
                            <div>
                                <h3>Theo sát hành trình</h3>
                                <p>Giờ đón, điểm hẹn và thay đổi phát sinh được cập nhật kịp thời.</p>
                            </div>
                        </div>
                        <div class="miu-commitment-item">
                            <i class="fa fa-check"></i>
                            <div>
                                <h3>Lắng nghe phản hồi</h3>
                                <p>Ghi nhận trải nghiệm thực tế để cải thiện dịch vụ cho những chuyến đi sau.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="miu-commitment-photo" aria-hidden="true"></div>
            </div>
        </div>
    </section>

    <section class="miu-section miu-contact" id="lien-he">
        <div class="container">
            <div class="miu-section-head">
                <div>
                    <span class="miu-eyebrow"><i class="fa fa-phone"></i> Liên hệ</span>
                    <h2 class="miu-heading">Bắt đầu chuyến đi cùng Miu Travel</h2>
                    <p class="miu-copy">
                        Cần tư vấn tour, khách sạn, thuê xe hoặc lịch trình riêng?<br>
                        Gửi thông tin và chia sẻ vài mong muốn của bạn, đội ngũ Miu Travel sẽ liên hệ lại để gợi ý
                        phương án phù hợp.
                    </p>
                </div>
            </div>

            <div class="miu-contact-grid">
                <div class="miu-contact-panel">
                    <div class="miu-contact-intro">
                        <span><i class="fa fa-comments-o"></i> Tư vấn trực tiếp</span>
                        <strong>Nói rõ nhu cầu, nhận gợi ý phù hợp</strong>
                        <p>
                            Miu Travel trao đổi nhanh về thời gian, số lượng khách và ngân sách trước khi gợi ý phương
                            án phù hợp.
                        </p>
                    </div>
                    <div class="miu-contact-line">
                        <i class="fa fa-map-marker"></i>
                        <div>
                            <span>Địa chỉ</span>
                            <p>Phường An Hải, Tp. Đà Nẵng</p>
                        </div>
                    </div>
                    <div class="miu-contact-line">
                        <i class="fa fa-phone"></i>
                        <div>
                            <span>Số điện thoại</span>
                            <a href="tel:0886733538">0886 733 538</a>
                        </div>
                    </div>
                    <div class="miu-contact-line">
                        <i class="fa fa-envelope"></i>
                        <div>
                            <span>Email</span>
                            <a href="mailto:letoantrung73@gmail.com">letoantrung73@gmail.com</a>
                        </div>
                    </div>
                    <div class="miu-contact-line">
                        <i class="fa fa-clock-o"></i>
                        <div>
                            <span>Giờ làm việc</span>
                            <p>8:00 - 17:30 <br> Thứ 2 - Thứ 7</p>
                        </div>
                    </div>
                </div>

                <div class="miu-form-panel">
                    <div class="contact-form-header">
                        <h2>Gửi yêu cầu tư vấn</h2>
                        <p>Để lại thông tin, Miu Travel sẽ liên hệ lại và gợi ý phương án phù hợp nhất.</p>
                    </div>

                    <?php if (session('success')): ?>
                    <div class="cf-alert cf-alert--success">
                        <i class="fa fa-check-circle fa-lg"></i>
                        <?php    echo e(session('success')); ?>

                    </div>
                    <?php endif; ?>
                    <?php if (session('error')): ?>
                    <div class="cf-alert cf-alert--error">
                        <i class="fa fa-times-circle fa-lg"></i>
                        <?php    echo e(session('error')); ?>

                    </div>
                    <?php endif; ?>

                    <form action="{{ route('contact.send') }}" method="POST" id="contact-form">
                        @csrf

                        <div class="cf-row">
                            <div class="cf-group">
                                <label>Họ và tên <span style="color:#ef4444;">*</span></label>
                                <div class="cf-input-wrap">
                                    <i class="fa fa-user cf-icon"></i>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nguyễn Văn A"
                                        required>
                                </div>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])):
    if (isset($message)) {
        $__messageOriginal = $message;
    }
    $message = $__bag->first($__errorArgs[0]); ?><span
                                    style="color:#ef4444;font-size:12px;margin-top:4px;"><?php    echo e($message); ?></span><?php    unset($message);
    if (isset($__messageOriginal)) {
        $message = $__messageOriginal;
    }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="cf-group">
                                <label>Số điện thoại <span style="color:#ef4444;">*</span></label>
                                <div class="cf-input-wrap">
                                    <i class="fa fa-phone cf-icon"></i>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="0886 733 538"
                                        required>
                                </div>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])):
    if (isset($message)) {
        $__messageOriginal = $message;
    }
    $message = $__bag->first($__errorArgs[0]); ?><span
                                    style="color:#ef4444;font-size:12px;margin-top:4px;"><?php    echo e($message); ?></span><?php    unset($message);
    if (isset($__messageOriginal)) {
        $message = $__messageOriginal;
    }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="cf-row">
                            <div class="cf-group">
                                <label>Email</label>
                                <div class="cf-input-wrap">
                                    <i class="fa fa-envelope cf-icon"></i>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        placeholder="example@gmail.com">
                                </div>
                            </div>

                            <div class="cf-group">
                                <label>Dịch vụ quan tâm</label>
                                <div class="cf-input-wrap">
                                    <i class="fa fa-compass cf-icon"></i>
                                    <select name="service">
                                        <option value="">-- Chọn dịch vụ --</option>
                                        <option value="tour_domestic" <?php echo e(old('service') == 'tour_domestic' ? 'selected' : ''); ?>>Tour du lịch</option>
                                        <option value="hotel" <?php echo e(old('service') == 'hotel' ? 'selected' : ''); ?>>
                                            Tư vấn nơi
                                            lưu trú</option>
                                        <option value="car" <?php echo e(old('service') == 'car' ? 'selected' : ''); ?>>
                                            Thuê
                                            xe</option>
                                        <option value="custom" <?php echo e(old('service') == 'custom' ? 'selected' : ''); ?>>
                                            Lịch trình
                                            theo yêu cầu</option>
                                        <option value="other" <?php echo e(old('service') == 'other' ? 'selected' : ''); ?>>
                                            Khác</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="cf-group cf-group--textarea">
                            <label>Nội dung <span style="color:#ef4444;">*</span></label>
                            <div class="cf-input-wrap">
                                <i class="fa fa-comment cf-icon"></i>
                                <textarea name="message"
                                    placeholder="Bạn cần tư vấn tour, khách sạn, xe hay lịch trình riêng?"
                                    required>{{ old('message') }}</textarea>
                            </div>
                            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])):
    if (isset($message)) {
        $__messageOriginal = $message;
    }
    $message = $__bag->first($__errorArgs[0]); ?><span
                                style="color:#ef4444;font-size:12px;margin-top:4px;"><?php    echo e($message); ?></span><?php    unset($message);
    if (isset($__messageOriginal)) {
        $message = $__messageOriginal;
    }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <button type="submit" class="cf-submit" id="contact-submit-btn">
                            <i class="fa fa-paper-plane-o"></i>
                            Gửi yêu cầu tư vấn
                        </button>

                        <div class="cf-guarantee">
                            <span class="cf-guarantee-item"><i class="fa fa-check-circle"></i> Miễn phí tư vấn</span>
                            <span class="cf-guarantee-item"><i class="fa fa-check-circle"></i> Phản hồi trong 24h</span>
                            <span class="cf-guarantee-item"><i class="fa fa-check-circle"></i> Bảo mật thông tin</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('page.common.listCommentHot', compact('comments'))
</div>
@stop

@section('script')
<script>
    var contactForm = document.getElementById('contact-form');

    if (contactForm) {
        contactForm.addEventListener('submit', function () {
            var btn = document.getElementById('contact-submit-btn');

            if (btn) {
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang gửi...';
                btn.disabled = true;
            }
        });
    }
</script>
@stop