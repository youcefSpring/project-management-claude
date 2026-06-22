@extends('layouts.sidebar')

@section('title', __('app.chat.title'))
@section('page-title', __('app.chat.title'))

@push('styles')
<style>
    /* Read vs unread conversations */
    .conv-item { color: #6c757d; transition: background-color .15s ease; }
    .conv-item .bi { color: #adb5bd; }
    .conv-item.unread {
        color: #1e293b;
        font-weight: 600;
        background-color: rgba(79, 70, 229, 0.07);
    }
    .conv-item.unread .bi { color: var(--primary-color); }
    .conv-item.active {
        background-color: rgba(79, 70, 229, 0.14);
        color: #1e293b;
        box-shadow: inset 3px 0 0 var(--primary-color);
    }
    .unread-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--primary-color);
        margin-right: 6px;
        vertical-align: middle;
    }
    [dir="rtl"] .unread-dot { margin-right: 0; margin-left: 6px; }
    [dir="rtl"] .conv-item.active { box-shadow: inset -3px 0 0 var(--primary-color); }
</style>
@endpush

@section('content')
<div class="row g-3" style="height: calc(100vh - 130px);">
    <!-- Conversations -->
    <div class="col-md-4 col-lg-3 h-100">
        <div class="card h-100 d-flex flex-column">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>{{ __('app.chat.conversations') }}</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#newDirectModal">
                            <i class="bi bi-person-plus me-2"></i>{{ __('app.chat.new_direct') }}
                        </button></li>
                        @if($canAnnounce)
                        <li><button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#newAnnouncementModal">
                            <i class="bi bi-megaphone me-2"></i>{{ __('app.chat.new_announcement') }}
                        </button></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="card-body p-0 overflow-auto" id="conversationList">
                <div class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm"></div></div>
            </div>
        </div>
    </div>

    <!-- Thread -->
    <div class="col-md-8 col-lg-9 h-100">
        <div class="card h-100 d-flex flex-column">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-chat-text"></i>
                <h6 class="mb-0" id="threadTitle">{{ __('app.chat.select_conversation') }}</h6>
            </div>
            <div class="card-body overflow-auto flex-grow-1 bg-light" id="messageList">
                <div class="text-center text-muted py-5">
                    <i class="bi bi-chat-square-text fs-1 opacity-25"></i>
                    <p class="mt-2">{{ __('app.chat.select_conversation') }}</p>
                </div>
            </div>
            <div class="card-footer" id="composer" style="display:none;">
                <form id="messageForm" enctype="multipart/form-data">
                    <div id="filePreview" class="d-flex flex-wrap gap-2 mb-2"></div>
                    <div class="input-group">
                        <label class="btn btn-outline-secondary mb-0" title="{{ __('app.chat.attach') }}">
                            <i class="bi bi-paperclip"></i>
                            <input type="file" name="attachments[]" id="fileInput" multiple class="d-none"
                                   accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.csv,.zip">
                        </label>
                        <input type="text" class="form-control" id="messageBody" name="body"
                               placeholder="{{ __('app.chat.type_message') }}" autocomplete="off">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- New Direct Message modal -->
<div class="modal fade" id="newDirectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('app.chat.new_direct') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">{{ __('app.chat.choose_recipient') }}</label>
                <select class="form-select" id="directUser">
                    @foreach($orgUsers as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">{{ __('app.cancel') ?? 'Cancel' }}</button>
                <button class="btn btn-primary" id="startDirectBtn">{{ __('app.chat.new_message') }}</button>
            </div>
        </div>
    </div>
</div>

@if($canAnnounce)
<!-- New Announcement modal -->
<div class="modal fade" id="newAnnouncementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-megaphone me-2"></i>{{ __('app.chat.new_announcement') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="announcementForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.chat.announcement_title') }}</label>
                        <input type="text" class="form-control" name="title" maxlength="160" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">{{ __('app.chat.type_message') }}</label>
                        <textarea class="form-control" name="body" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('app.cancel') ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('app.chat.post') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
(function () {
    const me = parseInt(window.userId, 10);
    const urls = {
        conversations: @json(route('chat.conversations')),
        direct: @json(route('chat.direct')),
        announcement: @json(route('chat.announcements.store')),
        messagesBase: @json(url('chat')), // + /{id}/messages
    };
    const i18n = { noConv: @json(__('app.chat.no_conversations')), noMsg: @json(__('app.chat.no_messages')),
                   download: @json(__('app.chat.download')), select: @json(__('app.chat.select_conversation')) };
    const esc = (s) => { const d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; };
    const fmtSize = (b) => b > 1048576 ? (b/1048576).toFixed(1)+' MB' : Math.max(1, Math.round(b/1024))+' KB';

    const listEl = document.getElementById('conversationList');
    const msgEl = document.getElementById('messageList');
    const titleEl = document.getElementById('threadTitle');
    const composer = document.getElementById('composer');
    let rendered = new Set();

    async function loadConversations() {
        try {
            const res = await axios.get(urls.conversations);
            const items = res.data.data || [];
            if (!items.length) { listEl.innerHTML = '<div class="text-center text-muted py-4">' + esc(i18n.noConv) + '</div>'; return; }
            listEl.innerHTML = items.map(function (c) {
                const icon = c.type === 'announcement' ? 'bi-megaphone' : (c.type === 'project' ? 'bi-folder' : 'bi-person');
                const unread = c.unread > 0;
                const badge = unread ? '<span class="badge bg-danger rounded-pill">' + c.unread + '</span>' : '';
                return '<button type="button" class="list-group-item list-group-item-action border-0 border-bottom w-100 text-start d-flex align-items-center justify-content-between conv-item' + (unread ? ' unread' : '') + '" data-id="' + c.id + '" data-title="' + esc(c.title) + '">'
                    + '<span class="text-truncate">'
                    + (unread ? '<span class="unread-dot"></span>' : '')
                    + '<i class="bi ' + icon + ' me-2"></i>' + esc(c.title) + '</span>' + badge + '</button>';
            }).join('');
            listEl.querySelectorAll('.conv-item').forEach(function (b) {
                if (window.__activeConversation && b.dataset.id == window.__activeConversation) {
                    b.classList.add('active');
                }
                b.addEventListener('click', function () { openConversation(b.dataset.id, b.dataset.title); });
            });
        } catch (e) { /* ignore */ }
    }

    function attachmentHtml(a) {
        if (a.is_image) {
            return '<a href="' + a.url + '" target="_blank" rel="noopener" class="d-inline-block me-2 mb-2">'
                + '<img src="' + a.url + '" alt="' + esc(a.name) + '" style="max-width:160px;max-height:160px;border-radius:8px;object-fit:cover;"></a>';
        }
        return '<a href="' + a.download_url + '" class="d-flex align-items-center gap-2 border rounded p-2 mb-2 text-decoration-none bg-white" style="max-width:280px;">'
            + '<i class="bi bi-file-earmark-arrow-down fs-4 text-primary"></i>'
            + '<span class="flex-grow-1 text-truncate"><span class="d-block text-truncate">' + esc(a.name) + '</span>'
            + '<small class="text-muted">' + fmtSize(a.size) + '</small></span>'
            + '<i class="bi bi-download text-muted"></i></a>';
    }

    function messageHtml(m) {
        const mine = m.user_id === me;
        const atts = (m.attachments || []).map(attachmentHtml).join('');
        const bubble = '<div class="p-2 px-3 rounded-3 ' + (mine ? 'bg-primary text-white' : 'bg-white border') + '" style="max-width:560px;">'
            + (mine ? '' : '<div class="small fw-bold mb-1">' + esc(m.user_name) + '</div>')
            + (m.body ? '<div style="white-space:pre-wrap;word-break:break-word;">' + esc(m.body) + '</div>' : '')
            + (atts ? '<div class="mt-2">' + atts + '</div>' : '')
            + '<div class="small ' + (mine ? 'text-white-50' : 'text-muted') + ' mt-1 text-end">' + esc(m.time) + '</div></div>';
        return '<div class="d-flex mb-2 ' + (mine ? 'justify-content-end' : 'justify-content-start') + '" data-mid="' + m.id + '">' + bubble + '</div>';
    }

    function appendMessages(messages) {
        let added = false;
        messages.forEach(function (m) {
            if (rendered.has(m.id)) { return; }
            rendered.add(m.id);
            msgEl.insertAdjacentHTML('beforeend', messageHtml(m));
            if (m.id > (window.__lastMessageId || 0)) { window.__lastMessageId = m.id; }
            added = true;
        });
        if (added) { msgEl.scrollTop = msgEl.scrollHeight; }
    }
    window.__onChatMessages = appendMessages;

    async function openConversation(id, title) {
        window.__activeConversation = id;
        window.__lastMessageId = 0;
        rendered = new Set();
        titleEl.textContent = title || '';
        msgEl.innerHTML = '<div class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm"></div></div>';
        listEl.querySelectorAll('.conv-item').forEach(b => b.classList.toggle('active', b.dataset.id == id));
        try {
            const res = await axios.get(urls.messagesBase + '/' + id + '/messages');
            const data = res.data;
            msgEl.innerHTML = '';
            if (!(data.data || []).length) { msgEl.innerHTML = '<div class="text-center text-muted py-5">' + esc(i18n.noMsg) + '</div>'; }
            appendMessages(data.data || []);
            composer.style.display = data.can_post ? 'block' : 'none';
            loadConversations(); // refresh unread badges
        } catch (e) {
            msgEl.innerHTML = '<div class="alert alert-danger m-3">Failed to load.</div>';
        }
    }

    // Send
    document.getElementById('messageForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!window.__activeConversation) { return; }
        const form = e.target;
        const btn = form.querySelector('[type="submit"]');
        const body = document.getElementById('messageBody').value;
        const files = document.getElementById('fileInput').files;
        if (!body.trim() && !files.length) { return; }
        btn.disabled = true;
        try {
            const res = await axios.post(urls.messagesBase + '/' + window.__activeConversation + '/messages', new FormData(form), {
                headers: { 'Accept': 'application/json' }
            });
            appendMessages([res.data.data]);
            form.reset();
            document.getElementById('filePreview').innerHTML = '';
        } catch (err) {
            window.ajaxToast && ajaxToast((err.response && err.response.data && err.response.data.message) || 'Failed to send.', 'error');
        } finally { btn.disabled = false; }
    });

    // File preview chips
    document.getElementById('fileInput').addEventListener('change', function () {
        const prev = document.getElementById('filePreview');
        prev.innerHTML = Array.from(this.files).map(function (f) {
            if (f.type.startsWith('image/')) {
                return '<img src="' + URL.createObjectURL(f) + '" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">';
            }
            return '<span class="badge bg-secondary"><i class="bi bi-file-earmark me-1"></i>' + esc(f.name) + '</span>';
        }).join('');
    });

    // New direct
    document.getElementById('startDirectBtn').addEventListener('click', async function () {
        const uid = document.getElementById('directUser').value;
        try {
            const res = await axios.post(urls.direct, { user_id: uid }, { headers: { 'Accept': 'application/json' } });
            bootstrap.Modal.getInstance(document.getElementById('newDirectModal')).hide();
            await loadConversations();
            const sel = document.getElementById('directUser');
            openConversation(res.data.conversation_id, sel.options[sel.selectedIndex].text);
        } catch (e) { window.ajaxToast && ajaxToast('Failed.', 'error'); }
    });

    @if($canAnnounce)
    document.getElementById('announcementForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        try {
            const res = await axios.post(urls.announcement, new FormData(e.target), { headers: { 'Accept': 'application/json' } });
            bootstrap.Modal.getInstance(document.getElementById('newAnnouncementModal')).hide();
            e.target.reset();
            window.ajaxToast && ajaxToast(res.data.message || 'Posted.');
            await loadConversations();
            if (res.data.conversation_id) { openConversation(res.data.conversation_id, ''); }
        } catch (err) {
            window.ajaxToast && ajaxToast('Failed.', 'error');
        }
    });
    @endif

    // Init
    loadConversations();
    setInterval(loadConversations, 6000); // keep unread colors current
    @if($openConversationId)
        openConversation(@json($openConversationId), '');
    @endif
})();
</script>
@endpush
