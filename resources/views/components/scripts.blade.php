<!-- Component Scripts -->
<script>
    // CSRF Token Setup
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // Global Variables
    window.userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    window.userRole = document.querySelector('meta[name="user-role"]').getAttribute('content');
    window.appLocale = document.querySelector('meta[name="app-locale"]').getAttribute('content');

    // Sidebar Toggle - Removed for horizontal layout
    // function toggleSidebar() {
    //     document.querySelector('.sidebar').classList.toggle('show');
    // }

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