<!-- Component Styles -->
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --success-color: #198754;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #0dcaf0;
        --sidebar-width: 250px;
    }

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

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        z-index: 1000;
        transition: transform 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .sidebar .p-3 {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .sidebar .nav-pills {
        flex: 1;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    /* Compact navigation items */
    .sidebar .nav-link {
        padding: 0.5rem 0.75rem;
        margin: 1px 0;
        font-size: 0.9rem;
    }

    .sidebar .nav-link i {
        width: 16px;
        margin-right: 8px;
    }

    [dir="rtl"] .sidebar .nav-link i {
        margin-right: 0;
        margin-left: 8px;
    }

    [dir="rtl"] .sidebar {
        left: auto;
        right: 0;
    }

    /* LTR Sidebar (explicit) */
    [dir="ltr"] .sidebar {
        left: 0;
        right: auto;
    }

    .sidebar-collapsed .sidebar {
        transform: translateX(-100%);
    }

    [dir="rtl"] .sidebar-collapsed .sidebar {
        transform: translateX(100%);
    }

    .main-content {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    [dir="rtl"] .main-content {
        margin-left: 0;
        margin-right: var(--sidebar-width);
        transition: margin-right 0.3s ease;
    }

    [dir="ltr"] .main-content {
        margin-left: var(--sidebar-width);
        margin-right: 0;
        transition: margin-left 0.3s ease;
    }

    .sidebar-collapsed .main-content {
        margin-left: 0;
    }

    [dir="rtl"] .sidebar-collapsed .main-content {
        margin-right: 0;
    }

    /* Navigation Styles */
    .nav-link {
        color: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        margin: 2px 0;
        transition: all 0.3s ease;
    }

    .nav-link:hover,
    .nav-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-link i {
        width: 20px;
        margin-right: 10px;
    }

    [dir="rtl"] .nav-link i {
        margin-right: 0;
        margin-left: 10px;
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

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        [dir="rtl"] .sidebar {
            transform: translateX(100%);
        }

        [dir="ltr"] .sidebar {
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: 0;
        }

        [dir="rtl"] .main-content {
            margin-right: 0;
        }

        [dir="ltr"] .main-content {
            margin-left: 0;
        }

        .sidebar.show {
            transform: translateX(0);
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
</style>