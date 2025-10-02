import './bootstrap';
import 'bootstrap';
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Global app object for shared functionality
window.ProjectApp = {
    // Language switching
    switchLanguage(lang) {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('lang', lang);
        window.location.href = currentUrl.toString();
    },

    // Time formatting utilities
    formatDuration(hours) {
        if (hours < 1) {
            return `${Math.round(hours * 60)}m`;
        }
        const h = Math.floor(hours);
        const m = Math.round((hours - h) * 60);
        return m > 0 ? `${h}h ${m}m` : `${h}h`;
    },

    // Date formatting
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString();
    },

    formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString();
    },

    // Status badge colors
    getStatusColor(status) {
        const colors = {
            'pending': 'warning',
            'in_progress': 'info',
            'completed': 'success',
            'blocked': 'danger',
            'on_hold': 'secondary',
            'cancelled': 'dark',
            'active': 'success',
            'inactive': 'secondary'
        };
        return colors[status] || 'secondary';
    },

    // Priority colors
    getPriorityColor(priority) {
        const colors = {
            'high': 'danger',
            'medium': 'warning',
            'low': 'info'
        };
        return colors[priority] || 'secondary';
    },

    // Confirmation dialogs
    confirmDelete(message = 'Are you sure you want to delete this item?') {
        return confirm(message);
    },

    // Toast notifications
    showToast(message, type = 'success') {
        // Create toast element
        const toastContainer = document.getElementById('toast-container') || this.createToastContainer();
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toast);

        // Initialize and show the toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remove from DOM after hiding
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    },

    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1055';
        document.body.appendChild(container);
        return container;
    },

    // Timer functionality
    timer: {
        startTime: null,
        timerInterval: null,
        isRunning: false,

        start() {
            if (this.isRunning) return;

            this.startTime = Date.now();
            this.isRunning = true;

            this.timerInterval = setInterval(() => {
                this.updateDisplay();
            }, 1000);

            this.updateButtons();
        },

        stop() {
            if (!this.isRunning) return;

            clearInterval(this.timerInterval);
            this.isRunning = false;

            const duration = this.getElapsedTime();
            this.reset();

            return duration;
        },

        reset() {
            clearInterval(this.timerInterval);
            this.startTime = null;
            this.isRunning = false;
            this.updateDisplay();
            this.updateButtons();
        },

        getElapsedTime() {
            if (!this.startTime) return 0;
            return (Date.now() - this.startTime) / 1000 / 3600; // Return hours
        },

        updateDisplay() {
            const display = document.getElementById('timer-display');
            if (!display) return;

            const elapsed = this.getElapsedTime();
            const hours = Math.floor(elapsed);
            const minutes = Math.floor((elapsed - hours) * 60);
            const seconds = Math.floor(((elapsed - hours) * 60 - minutes) * 60);

            display.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        },

        updateButtons() {
            const startBtn = document.getElementById('start-timer-btn');
            const stopBtn = document.getElementById('stop-timer-btn');

            if (startBtn) startBtn.style.display = this.isRunning ? 'none' : 'inline-block';
            if (stopBtn) stopBtn.style.display = this.isRunning ? 'inline-block' : 'none';
        }
    },

    // Form helpers
    serializeForm(form) {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        return data;
    },

    // AJAX helpers
    async makeRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        };

        const mergedOptions = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, mergedOptions);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Request failed:', error);
            this.showToast('Request failed. Please try again.', 'danger');
            throw error;
        }
    },

    // Chart helpers (if Chart.js is loaded)
    createChart(ctx, config) {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js not loaded');
            return null;
        }
        return new Chart(ctx, config);
    }
};

// Initialize components when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    });

    // Form validation feedback
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Global timer controls
    const startTimerBtn = document.getElementById('start-timer-btn');
    const stopTimerBtn = document.getElementById('stop-timer-btn');

    if (startTimerBtn) {
        startTimerBtn.addEventListener('click', () => {
            ProjectApp.timer.start();
        });
    }

    if (stopTimerBtn) {
        stopTimerBtn.addEventListener('click', async () => {
            const duration = ProjectApp.timer.stop();

            // Show time logging modal or form
            const taskId = stopTimerBtn.dataset.taskId;
            if (taskId && duration > 0) {
                // You can implement a modal here to log the time
                ProjectApp.showToast(`Timer stopped. Duration: ${ProjectApp.formatDuration(duration)}`);
            }
        });
    }
});

// Make ProjectApp globally available
window.ProjectApp = ProjectApp;