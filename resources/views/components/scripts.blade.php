<!-- Component Scripts -->
<script>
    // CSRF Token Setup
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // Global Variables
    window.userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    window.userRole = document.querySelector('meta[name="user-role"]').getAttribute('content');
    window.appLocale = document.querySelector('meta[name="app-locale"]').getAttribute('content');

    // Vertical sidebar drawer toggle (mobile)
    (function () {
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');
        if (!sidebar) return;

        const open = () => { sidebar.classList.add('show'); overlay && overlay.classList.add('show'); };
        const close = () => { sidebar.classList.remove('show'); overlay && overlay.classList.remove('show'); };

        toggle && toggle.addEventListener('click', function () {
            sidebar.classList.contains('show') ? close() : open();
        });
        overlay && overlay.addEventListener('click', close);
        // Close drawer after navigating on mobile
        sidebar.querySelectorAll('.nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) close();
            });
        });

        // Desktop collapse (icon-only), persisted
        const collapseBtn = document.getElementById('sidebarCollapse');
        if (localStorage.getItem('sidebarCollapsed') === '1') {
            document.body.classList.add('sidebar-collapsed');
        }
        collapseBtn && collapseBtn.addEventListener('click', function () {
            const collapsed = document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
        });
    })();

    // Initialise Bootstrap tooltips (used by list action icons)
    (function () {
        if (!window.bootstrap || !bootstrap.Tooltip) { return; }
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
            new bootstrap.Tooltip(el);
        });
    })();

    // Global chat + notifications poller (every 3s)
    (function () {
        const pollUrl = @json(route('chat.poll'));
        window.__activeConversation = window.__activeConversation || null;
        window.__lastMessageId = window.__lastMessageId || 0;

        function setBadge(el, count) {
            if (!el) { return; }
            if (count > 0) { el.textContent = count > 99 ? '99+' : count; el.classList.remove('d-none'); }
            else { el.classList.add('d-none'); }
        }

        async function poll() {
            try {
                const params = {};
                if (window.__activeConversation) {
                    params.conversation = window.__activeConversation;
                    params.after = window.__lastMessageId || 0;
                }
                const res = await axios.get(pollUrl, { params });
                const d = res.data || {};
                setBadge(document.getElementById('chatUnreadBadge'), d.chat_unread || 0);
                setBadge(document.getElementById('notifUnreadBadge'), d.notifications_unread || 0);
                if (Array.isArray(d.messages) && d.messages.length && typeof window.__onChatMessages === 'function') {
                    window.__onChatMessages(d.messages);
                }
            } catch (e) { /* ignore transient poll errors */ }
        }

        poll();
        setInterval(poll, 3000);
    })();

    // Language switching is now handled by direct links with MCamara

    // Global AJAX Setup - disabled to prevent loading overlay issues
    // axios.interceptors can be enabled per-page when needed

    // Form helpers
    function showSuccess(message) {
        const alert = `<div class="alert alert-success alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        document.querySelector('main').insertAdjacentHTML('afterbegin', alert);
    }

    function showError(message) {
        const alert = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        document.querySelector('main').insertAdjacentHTML('afterbegin', alert);
    }

    // Enhanced navbar functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                openDropdowns.forEach(dropdown => {
                    const bsDropdown = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                    if (bsDropdown) {
                        bsDropdown.hide();
                    }
                });
            }
        });

        // Notification click handling
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                // Mark as read or handle notification click
                this.classList.add('opacity-75');
            });
        });

        // Profile dropdown enhancements
        const profileDropdown = document.querySelector('.dropdown:last-child .dropdown-toggle');
        if (profileDropdown) {
            profileDropdown.addEventListener('show.bs.dropdown', function() {
                // Add any profile-specific functionality here
            });
        }
    });
</script>