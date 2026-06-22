<!-- Global AJAX modal + toast host -->
<div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ajaxModalBody"></div>
        </div>
    </div>
</div>

<!-- Reusable confirmation dialog -->
<div class="modal fade" id="ajaxConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 mb-3"
                     style="width: 64px; height: 64px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="mb-2">{{ __('app.messages.confirm_delete') ?? 'Confirm deletion' }}</h5>
                <p class="text-muted small mb-4" id="ajaxConfirmMessage"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ __('app.cancel') ?? 'Cancel' }}</button>
                    <button type="button" class="btn btn-danger px-4" id="ajaxConfirmOk">
                        <i class="bi bi-trash me-1"></i>{{ __('app.delete') ?? 'Delete' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="ajaxToasts" style="z-index: 1090;"></div>

<script>
(function () {
    const modalEl = document.getElementById('ajaxModal');
    const bsModal = modalEl ? new bootstrap.Modal(modalEl) : null;
    const titleEl = document.getElementById('ajaxModalTitle');
    const bodyEl = document.getElementById('ajaxModalBody');
    let refreshTarget = null;

    // Promise-based confirmation using the styled modal (replaces window.confirm)
    const confirmEl = document.getElementById('ajaxConfirmModal');
    const bsConfirm = confirmEl ? new bootstrap.Modal(confirmEl) : null;
    const confirmMsg = document.getElementById('ajaxConfirmMessage');
    const confirmOk = document.getElementById('ajaxConfirmOk');

    function confirmDialog(message) {
        return new Promise(function (resolve) {
            if (!bsConfirm) { resolve(window.confirm(message)); return; }
            confirmMsg.textContent = message || '';
            let settled = false;
            const onOk = function () { settled = true; bsConfirm.hide(); resolve(true); };
            const onHidden = function () {
                confirmOk.removeEventListener('click', onOk);
                confirmEl.removeEventListener('hidden.bs.modal', onHidden);
                if (!settled) { resolve(false); }
            };
            confirmOk.addEventListener('click', onOk);
            confirmEl.addEventListener('hidden.bs.modal', onHidden);
            bsConfirm.show();
        });
    }

    function toast(message, type) {
        const host = document.getElementById('ajaxToasts');
        if (!host) { return; }
        const el = document.createElement('div');
        el.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : 'success') + ' border-0';
        el.setAttribute('role', 'alert');
        el.innerHTML = '<div class="d-flex"><div class="toast-body">' + (message || '') +
            '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
        host.appendChild(el);
        const t = new bootstrap.Toast(el, { delay: 3500 });
        t.show();
        el.addEventListener('hidden.bs.toast', () => el.remove());
    }
    window.ajaxToast = toast;

    // Pull the main form out of a fetched page (skip sidebar/search forms).
    function extractForm(html) {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        return doc.querySelector('main form') || doc.querySelector('form[action*="store"], form[action*="update"]');
    }

    function clearErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback.ajax-err').forEach(e => e.remove());
        const a = form.querySelector('.ajax-form-alert'); if (a) { a.remove(); }
    }

    function showErrors(form, data) {
        clearErrors(form);
        const errors = (data && data.errors) || {};
        Object.keys(errors).forEach(function (field) {
            const input = form.querySelector('[name="' + field + '"], [name="' + field + '[]"]');
            if (input) {
                input.classList.add('is-invalid');
                const fb = document.createElement('div');
                fb.className = 'invalid-feedback ajax-err d-block';
                fb.textContent = errors[field][0];
                input.parentNode.appendChild(fb);
            }
        });
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger ajax-form-alert';
        alert.textContent = (data && data.message) || 'Validation failed.';
        form.prepend(alert);
    }

    async function refresh(selector) {
        try {
            const res = await axios.get(window.location.href, { headers: { 'X-Partial': '1' } });
            const doc = new DOMParser().parseFromString(res.data, 'text/html');
            const fresh = doc.querySelector(selector);
            const current = document.querySelector(selector);
            if (fresh && current) { current.innerHTML = fresh.innerHTML; return true; }
        } catch (e) { /* fall through */ }
        return false;
    }

    // Open a form in the modal
    document.addEventListener('click', async function (e) {
        const trigger = e.target.closest('[data-modal-url]');
        if (!trigger) { return; }
        e.preventDefault();
        refreshTarget = trigger.getAttribute('data-refresh');
        titleEl.textContent = trigger.getAttribute('data-modal-title') || '';
        bodyEl.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
        bsModal.show();
        try {
            const res = await axios.get(trigger.getAttribute('data-modal-url'), { headers: { 'X-Modal': '1' } });
            const form = extractForm(res.data);
            if (!form) { bodyEl.innerHTML = '<div class="alert alert-warning mb-0">Form not found.</div>'; return; }
            form.classList.add('ajax-form');
            bodyEl.innerHTML = '';
            bodyEl.appendChild(form);
        } catch (err) {
            bodyEl.innerHTML = '<div class="alert alert-danger mb-0">Failed to load form.</div>';
        }
    });

    // Submit any ajax form (modal forms or inline .ajax-form)
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        if (!form.classList.contains('ajax-form')) { return; }
        e.preventDefault();
        const btn = form.querySelector('[type="submit"]');
        const original = btn ? btn.innerHTML : '';
        if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>'; }
        const target = refreshTarget || form.getAttribute('data-refresh');
        try {
            const res = await axios.post(form.getAttribute('action'), new FormData(form), {
                headers: { 'Accept': 'application/json' }
            });
            if (bsModal) { bsModal.hide(); }
            toast((res.data && res.data.message) || 'Saved.');
            let refreshed = false;
            if (target) { refreshed = await refresh(target); }
            if (!refreshed) { window.location.assign((res.data && res.data.redirect) || window.location.href); }
            form.reset();
        } catch (err) {
            if (err.response && err.response.status === 422) {
                showErrors(form, err.response.data);
            } else {
                toast((err.response && err.response.data && err.response.data.message) || 'Something went wrong.', 'error');
            }
        } finally {
            if (btn) { btn.disabled = false; btn.innerHTML = original; }
        }
    });

    // AJAX delete
    document.addEventListener('click', async function (e) {
        const trigger = e.target.closest('[data-ajax-delete]');
        if (!trigger) { return; }
        e.preventDefault();
        const ok = await confirmDialog(trigger.getAttribute('data-confirm') || 'Are you sure?');
        if (!ok) { return; }
        const target = trigger.getAttribute('data-refresh');
        try {
            const res = await axios.delete(trigger.getAttribute('data-ajax-delete'), { headers: { 'Accept': 'application/json' } });
            toast((res.data && res.data.message) || 'Deleted.');
            const row = trigger.closest('[data-row]');
            if (target) { await refresh(target); }
            else if (row) { row.remove(); }
            else { window.location.reload(); }
        } catch (err) {
            toast((err.response && err.response.data && err.response.data.message) || 'Delete failed.', 'error');
        }
    });
})();
</script>
