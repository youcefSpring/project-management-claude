<!-- Component Styles -->
<style>
    :root {
        /* Palette shared with the landing page (see resources/views/landing.blade.php) */
        --dz-deep: #0B2A22;
        --dz-green: #0F6B4F;
        --dz-bright: #21A97C;
        --dz-sand: #F3EFE4;
        --dz-clay: #C1592B;

        --primary-color: #0F6B4F;
        --primary-hover: #21A97C;
        --secondary-color: #6b7d76;
        --success-color: #21A97C;
        --danger-color: #C1592B;
        --warning-color: #E9B949;
        --info-color: #5B9BD5;
        --sidebar-width: 260px;
        --brand-gradient: linear-gradient(160deg, #0B2A22 0%, #0F6B4F 100%);
    }

    /* Align core accents with the landing page (deep green / zellige clay) */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        border-radius: 10px;
        font-weight: 600;
    }
    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: var(--primary-hover) !important;
        border-color: var(--primary-hover) !important;
    }
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        border-radius: 10px;
    }
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .bg-primary { background-color: var(--primary-color) !important; }
    .text-primary { color: var(--primary-color) !important; }
    a { color: var(--primary-color); }

    /* RTL Support */
    [dir="rtl"] {
        text-align: right;
    }

    [dir="rtl"] .navbar-brand {
        margin-right: 0;
        margin-left: 1rem;
    }

    /* RTL Bootstrap adjustments */
    [dir="rtl"] .me-1, [dir="rtl"] .me-2, [dir="rtl"] .me-3 {
        margin-right: 0 !important;
        margin-left: var(--bs-gutter-x) !important;
    }

    [dir="rtl"] .ms-1, [dir="rtl"] .ms-2, [dir="rtl"] .ms-3 {
        margin-left: 0 !important;
        margin-right: var(--bs-gutter-x) !important;
    }

    [dir="rtl"] .float-end {
        float: left !important;
    }

    [dir="rtl"] .float-start {
        float: right !important;
    }

    [dir="rtl"] .text-start {
        text-align: right !important;
    }

    [dir="rtl"] .text-end {
        text-align: left !important;
    }

    /* RTL Navigation */
    [dir="rtl"] .nav-link {
        text-align: right;
    }

    [dir="rtl"] .nav-link i {
        margin-left: 0.5rem;
        margin-right: 0;
    }

    /* RTL Cards */
    [dir="rtl"] .card-body {
        text-align: right;
    }

    [dir="rtl"] .card-title {
        text-align: right;
    }

    /* RTL Forms */
    [dir="rtl"] .form-label {
        text-align: right;
    }

    [dir="rtl"] .input-group {
        direction: rtl;
    }

    [dir="rtl"] .input-group .form-control {
        text-align: right;
    }

    /* RTL Dropdowns */
    [dir="rtl"] .dropdown-menu {
        left: auto;
        right: 0;
    }

    [dir="rtl"] .dropdown-menu-end {
        left: 0;
        right: auto;
    }

    /* RTL Tables */
    [dir="rtl"] .table th,
    [dir="rtl"] .table td {
        text-align: right;
    }

    /* RTL Badges */
    [dir="rtl"] .badge {
        direction: rtl;
    }

    /* RTL Buttons */
    [dir="rtl"] .btn i {
        margin-left: 0.5rem;
        margin-right: 0;
    }

    [dir="rtl"] .btn-group {
        direction: rtl;
    }

    /* RTL Progress bars */
    [dir="rtl"] .progress {
        direction: ltr; /* Keep progress direction LTR for correct visual flow */
    }

    /* RTL Breadcrumbs */
    [dir="rtl"] .breadcrumb {
        direction: rtl;
    }

    /* RTL Alerts */
    [dir="rtl"] .alert {
        text-align: right;
    }

    /* Typography improvements for RTL */
    [lang="ar"], [dir="rtl"] {
        font-family: 'Noto Sans Arabic', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        letter-spacing: normal;
    }

    [lang="ar"] h1, [lang="ar"] h2, [lang="ar"] h3, [lang="ar"] h4, [lang="ar"] h5, [lang="ar"] h6,
    [dir="rtl"] h1, [dir="rtl"] h2, [dir="rtl"] h3, [dir="rtl"] h4, [dir="rtl"] h5, [dir="rtl"] h6 {
        font-family: 'Noto Sans Arabic', serif;
        font-weight: 600;
    }

    /* RTL specific spacing */
    [dir="rtl"] .container-fluid {
        padding-right: 15px;
        padding-left: 15px;
    }

    /* Fix Bootstrap RTL margin utilities */
    [dir="rtl"] .me-auto {
        margin-left: auto !important;
        margin-right: 0 !important;
    }

    [dir="rtl"] .ms-auto {
        margin-right: auto !important;
        margin-left: 0 !important;
    }

    /* RTL Icon positioning */
    [dir="rtl"] .bi {
        transform: scaleX(-1);
    }

    [dir="rtl"] .bi-arrow-left {
        transform: scaleX(1);
    }

    [dir="rtl"] .bi-arrow-right {
        transform: scaleX(1);
    }

    /* LTR explicit styles */
    [dir="ltr"] {
        text-align: left;
    }

    [dir="ltr"] .nav-link {
        text-align: left;
    }

    [dir="ltr"] .nav-link i {
        margin-right: 0.5rem;
        margin-left: 0;
    }

    [dir="ltr"] .card-body {
        text-align: left;
    }

    [dir="ltr"] .form-label {
        text-align: left;
    }

    [dir="ltr"] .table th,
    [dir="ltr"] .table td {
        text-align: left;
    }

    [dir="ltr"] .alert {
        text-align: left;
    }

    [dir="ltr"] .dropdown-menu {
        left: 0;
        right: auto;
    }

    [dir="ltr"] .navbar-brand {
        margin-left: 0;
        margin-right: 1rem;
    }

    /* Language Switcher Styles */
    .dropdown-item.active {
        background-color: var(--primary-color);
        color: white;
    }

    .dropdown-item.active:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .language-dropdown .dropdown-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .language-dropdown .dropdown-item i.bi-check2 {
        color: #28a745;
    }

    /* RTL Search box */
    [dir="rtl"] .input-group .btn {
        border-radius: 0.375rem 0 0 0.375rem;
    }

    [dir="rtl"] .input-group .form-control {
        border-radius: 0 0.375rem 0.375rem 0;
    }

    /* Sidebar Language Dropdown */
    .sidebar .dropdown-menu {
        background-color: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-left: 1rem;
        min-width: 200px;
    }

    .sidebar .dropdown-menu .dropdown-item {
        color: #333;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }

    .sidebar .dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .sidebar .dropdown-menu .dropdown-item.active {
        background-color: var(--primary-color);
        color: white;
    }

    .sidebar .dropdown-menu .dropdown-item.active:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .sidebar .dropdown-menu .dropdown-item i {
        width: 16px;
    }

    [dir="rtl"] .sidebar .dropdown-menu {
        margin-left: 0;
        margin-right: 1rem;
    }

    /* Vertical Sidebar (login-style gradient) */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        height: 100vh;
        height: 100dvh;
        width: var(--sidebar-width);
        background: var(--brand-gradient);
        color: #fff;
        z-index: 1040;
        box-shadow: 2px 0 16px rgba(0, 0, 0, 0.12);
        display: flex;
        flex-direction: column;
        overflow: visible;
        transition: transform 0.3s ease;
    }

    [dir="rtl"] .sidebar {
        left: auto;
        right: 0;
        box-shadow: -2px 0 16px rgba(0, 0, 0, 0.12);
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-grow: 1;
        min-width: 0;
        font-weight: 700;
        font-size: 1.1rem;
        color: #fff;
    }

    .sidebar-collapse-btn {
        flex-shrink: 0;
        border: none;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .sidebar-collapse-btn:hover {
        background: rgba(255, 255, 255, 0.22);
    }

    /* Desktop collapsed (icon-only) state */
    @media (min-width: 992px) {
        body.sidebar-collapsed .sidebar {
            width: 76px;
        }
        body.sidebar-collapsed .sidebar .sidebar-brand,
        body.sidebar-collapsed .sidebar .nav-link span,
        body.sidebar-collapsed .sidebar-footer .logout-text {
            display: none;
        }
        body.sidebar-collapsed .sidebar-header {
            justify-content: center;
        }
        body.sidebar-collapsed .sidebar .nav-link {
            justify-content: center;
        }
        body.sidebar-collapsed .sidebar .nav-link i {
            margin-right: 0;
            margin-left: 0;
        }
        body.sidebar-collapsed .sidebar-footer {
            flex-direction: column;
            align-items: center;
            gap: 0.6rem;
        }
        body.sidebar-collapsed .sidebar-footer .dropdown {
            width: 100%;
            display: flex;
            justify-content: center;
        }
        /* No caret next to the flag, and a tidy square toggle when collapsed */
        body.sidebar-collapsed .sidebar-footer .dropdown-toggle::after {
            display: none;
        }
        body.sidebar-collapsed .sidebar-footer #sidebarLanguageDropdown {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
            padding: 0 !important;
        }
        body.sidebar-collapsed .sidebar-footer form button {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        body.sidebar-collapsed .sidebar-footer form button i {
            margin: 0 !important;
        }
        body.sidebar-collapsed .main-content {
            margin-left: 76px;
        }
        [dir="rtl"] body.sidebar-collapsed .main-content {
            margin-left: 0;
            margin-right: 76px;
        }
    }

    .sidebar-brand .brand-mark {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.18);
        font-size: 1.2rem;
    }

    .sidebar .nav-pills {
        flex-direction: column;
        align-items: stretch;
        padding: 0.75rem;
        gap: 2px;
        flex-grow: 1;
        min-height: 0;
        overflow-y: auto;
    }

    .sidebar .nav-link {
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.85);
        padding: 0.7rem 0.9rem;
        border-radius: 10px;
        font-size: 0.95rem;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .sidebar .nav-link i {
        width: 22px;
        margin-right: 12px;
        font-size: 1.05rem;
        text-align: center;
    }

    [dir="rtl"] .sidebar .nav-link i {
        margin-right: 0;
        margin-left: 12px;
    }

    .sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        color: #fff;
        font-weight: 600;
    }

    #sidebarLanguageDropdown.dropdown-toggle::after {
        display: none;
    }

    .lang-name {
        white-space: nowrap;
    }

    .lang-flag {
        width: 22px;
        height: 16px;
        object-fit: cover;
        border-radius: 3px;
        box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.35);
        display: block;
    }

    .sidebar-footer {
        padding: 0.85rem;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    /* Mobile top bar + overlay */
    .app-topbar {
        display: none;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 1rem;
        background: #fff;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 0;
        z-index: 1030;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 1035;
    }

    .sidebar-overlay.show {
        display: block;
    }

    .main-content {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        overflow-x: hidden;
    }

    [dir="rtl"] .main-content {
        margin-left: 0;
        margin-right: var(--sidebar-width);
    }

    /* Card Styles */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 12px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 12px 12px 0 0 !important;
    }

    /* Status Badges */
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
    }

    .status-à_faire { background-color: #ffc107; color: #000; }
    .status-en_cours { background-color: #0dcaf0; color: #000; }
    .status-fait { background-color: #198754; color: #fff; }
    .status-terminé { background-color: #198754; color: #fff; }
    .status-annulé { background-color: #dc3545; color: #fff; }

    /* Loading States */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }

    /* Enhanced Dropdown positioning fixes */
    .dropdown-menu {
        z-index: 1050;
        min-width: 180px;
        max-width: 250px;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .navbar .dropdown-menu {
        z-index: 1051;
        margin-top: 0.5rem;
    }

    .navbar .dropdown-menu-end {
        right: 0 !important;
        left: auto !important;
    }

    /* Fix dropdown positioning in navbar */
    .navbar .dropdown {
        position: relative;
    }

    .navbar .dropdown-menu-end {
        transform: none !important;
    }

    /* Enhanced navbar component styling */
    .navbar .btn-outline-secondary {
        border-color: #dee2e6;
        color: #6c757d;
        background-color: #fff;
    }

    .navbar .btn-outline-secondary:hover {
        border-color: #adb5bd;
        color: #495057;
        background-color: #f8f9fa;
    }

    /* Notification badge styling */
    .navbar .position-relative .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.4rem;
    }

    /* Language dropdown enhancements */
    .language-dropdown {
        min-width: 200px;
    }

    .language-dropdown .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .language-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Profile dropdown enhancements */
    .navbar .dropdown-menu .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .navbar .dropdown-menu .dropdown-item i {
        width: 16px;
        margin-right: 0.5rem;
    }

    [dir="rtl"] .navbar .dropdown-menu .dropdown-item i {
        margin-right: 0;
        margin-left: 0.5rem;
    }

    /* Search form enhancements */
    .navbar .input-group .form-control {
        border-color: #dee2e6;
    }

    .navbar .input-group .btn {
        border-color: #dee2e6;
    }

    /* Notification item styling */
    .dropdown-menu .notification-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f1f3f4;
    }

    .dropdown-menu .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item .notification-title {
        font-weight: 500;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .notification-item .notification-time {
        font-size: 0.75rem;
        color: #6c757d;
    }

    /* Enhanced form styling for RTL/LTR */
    [dir="rtl"] .input-group {
        flex-direction: row-reverse;
    }

    [dir="ltr"] .input-group {
        flex-direction: row;
    }

    [dir="rtl"] .input-group .form-control:first-child {
        border-radius: 0 0.375rem 0.375rem 0;
    }

    [dir="rtl"] .input-group .form-control:last-child {
        border-radius: 0.375rem 0 0 0.375rem;
    }

    [dir="ltr"] .input-group .form-control:first-child {
        border-radius: 0.375rem 0 0 0.375rem;
    }

    [dir="ltr"] .input-group .form-control:last-child {
        border-radius: 0 0.375rem 0.375rem 0;
    }

    /* Better button spacing for RTL/LTR */
    [dir="rtl"] .btn i {
        margin-left: 0.5rem;
        margin-right: 0;
    }

    [dir="ltr"] .btn i {
        margin-right: 0.5rem;
        margin-left: 0;
    }

    /* Responsive: sidebar becomes an off-canvas drawer below lg */
    @media (max-width: 991.98px) {
        .app-topbar {
            display: flex;
        }

        .sidebar {
            transform: translateX(-100%);
        }

        [dir="rtl"] .sidebar {
            transform: translateX(100%);
        }

        .sidebar.show {
            transform: translateX(0) !important;
        }

        .main-content,
        [dir="rtl"] .main-content {
            margin-left: 0;
            margin-right: 0;
        }
    }

    /* Custom utilities */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hover-shadow {
        transition: box-shadow 0.15s ease-in-out;
    }

    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Dashboard stat tiles */
    .stats-card {
        background: linear-gradient(135deg, var(--dz-green) 0%, var(--dz-deep) 100%);
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stats-card.success { background: linear-gradient(135deg, var(--success-color) 0%, #146c43 100%); }
    .stats-card.warning { background: linear-gradient(135deg, var(--warning-color) 0%, #997404 100%); }
    .stats-card.info { background: linear-gradient(135deg, var(--info-color) 0%, #0a93b0 100%); }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.14);
    }

    .stats-card .stats-number { font-size: 2rem; font-weight: 700; }
    .stats-card .stats-label { opacity: 0.9; }

    .stats-card .stats-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.18);
        font-size: 1.5rem;
    }

    /* Skeleton loaders for async widgets */
    .skeleton {
        position: relative;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.08);
        border-radius: 0.4rem;
    }

    .stats-card .skeleton { background: rgba(255, 255, 255, 0.25); }

    .skeleton::after {
        content: "";
        position: absolute;
        inset: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.55), transparent);
        animation: skeleton-shimmer 1.3s infinite;
    }

    .skeleton-line { height: 0.85rem; margin-bottom: 0.5rem; }
    .skeleton-number { height: 2rem; width: 3.5rem; }

    @keyframes skeleton-shimmer {
        100% { transform: translateX(100%); }
    }

    /* Avatars */
    .avatar {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--secondary-color);
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        line-height: 1;
    }

    .avatar-sm { width: 32px; height: 32px; font-size: 0.85rem; }
    .avatar-xs { width: 24px; height: 24px; font-size: 0.7rem; }
    .avatar-primary { background: var(--primary-color); }
    .avatar-success { background: var(--success-color); }
    .avatar-warning { background: var(--warning-color); color: #212529; }

    /* Project show: page hero + timeline */
    .project-hero {
        background: var(--brand-gradient);
        color: #fff;
        border: none;
        border-radius: 0.75rem;
    }

    .project-hero .text-muted,
    .project-hero .hero-label { color: rgba(255, 255, 255, 0.75) !important; }

    .hero-stat { background: rgba(255, 255, 255, 0.12); border-radius: 0.6rem; }

    .activity-feed .activity-item:not(:last-child) { border-bottom: 1px solid rgba(0, 0, 0, 0.06); }

    /* Pure-CSS progress ring (no Chart.js dependency) */
    .progress-ring {
        position: relative;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: conic-gradient(#fff calc(var(--pct, 0) * 1%), rgba(255, 255, 255, 0.22) 0);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .progress-ring::before {
        content: "";
        position: absolute;
        width: 98px;
        height: 98px;
        border-radius: 50%;
        background: #5a31c4;
    }

    .progress-ring .progress-ring-label { position: relative; z-index: 1; line-height: 1.1; }
</style>