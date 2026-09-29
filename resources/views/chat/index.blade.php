@extends('layouts.admin')

@section('title', 'Chat - HRMS Pro')

@section('content')
<div class="container-fluid p-0" style="height: calc(100vh - 120px);">
    <div class="row g-0 h-100">

        {{-- ============ Sidebar: Conversation List ============ --}}
        <div class="col-md-4 col-lg-3 border-end d-flex flex-column bg-white" style="overflow:hidden;">
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-chat-dots-fill me-2 text-primary"></i>Messages</h6>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#newChatModal">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                </div>
                <input type="text" id="searchConversation" class="form-control form-control-sm" placeholder="Search conversations...">
            </div>

            <div class="flex-grow-1 overflow-auto" id="conversationList">
                @forelse($conversations as $conv)
                    @php
                        $other = $conv->participants->firstWhere('id', '!=', auth()->id());
                        $isActive = isset($conversation) && $conversation->id === $conv->id;
                        $unread = $conv->messages()
                            ->where('user_id', '!=', auth()->id())
                            ->where('created_at', '>', $conv->participants->find(auth()->id())?->pivot->last_read_at ?? '2000-01-01')
                            ->count();
                    @endphp
                    <a href="{{ route('chat.show', $conv) }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none border-bottom conversation-item {{ $isActive ? 'bg-primary bg-opacity-10' : '' }}" style="cursor:pointer;">
                        <div class="position-relative flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($other?->name ?? 'G') }}&size=44&background=random" class="rounded-circle" width="44" height="44">
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white" style="width:10px;height:10px;"></span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold small text-dark text-truncate">{{ $other?->name ?? $conv->title ?? 'Group' }}</span>
                                <small class="text-muted" style="font-size:0.7rem;">{{ $conv->latestMessage?->created_at?->format('h:i A') ?? '' }}</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted text-truncate">{{ Str::limit($conv->latestMessage?->body ?? 'No messages yet', 32) }}</small>
                                @if($unread > 0)
                                <span class="badge bg-primary rounded-pill" style="font-size:0.6rem;">{{ $unread }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-square-text fs-1 d-block mb-2"></i>
                        <p class="small">No conversations yet.<br>Start a new chat!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ============ Main: Chat Window ============ --}}
        <div class="col-md-8 col-lg-9 d-flex flex-column bg-light">
            @isset($conversation)
                @php $other = $conversation->participants->firstWhere('id', '!=', auth()->id()); @endphp

                {{-- Chat Header --}}
                <div class="d-flex align-items-center gap-3 p-3 bg-white border-bottom shadow-sm">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($other?->name ?? 'U') }}&size=40&background=random" class="rounded-circle" width="40" height="40">
                    <div>
                        <div class="fw-bold">{{ $other?->name ?? 'Group Chat' }}</div>
                        <small class="text-success"><i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Online</small>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="flex-grow-1 overflow-auto p-4" id="messageContainer" style="display:flex;flex-direction:column;">
                    @foreach($messages as $msg)
                        @php $isMine = $msg->user_id === auth()->id(); @endphp
                        <div class="d-flex mb-3 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}" data-msg-id="{{ $msg->id }}">
                            @if(!$isMine)
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($msg->user->name ?? 'U') }}&size=32&background=random" class="rounded-circle me-2 align-self-end" width="32" height="32">
                            @endif
                            <div class="chat-bubble {{ $isMine ? 'chat-bubble-mine' : 'chat-bubble-other' }}">
                                <p class="mb-1" style="font-size:0.9rem;">{{ $msg->body }}</p>
                                <small style="font-size:0.65rem; opacity:0.7;">{{ $msg->created_at->format('h:i A') }}</small>
                                @if($isMine)
                                    <small style="font-size:0.65rem; opacity:0.7;"> &nbsp;<i class="bi bi-check2-all"></i></small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <div id="messagesEnd"></div>
                </div>

                {{-- Input Box --}}
                <div class="p-3 bg-white border-top">
                    <form id="chatForm" class="d-flex align-items-center gap-2">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                        <input type="text" name="body" id="messageInput" class="form-control rounded-pill"
                            placeholder="Type a message..." autocomplete="off" required>
                        <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" style="width:42px;height:42px;min-width:42px;">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>

            @else
                {{-- Empty State --}}
                <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center text-muted">
                    <i class="bi bi-chat-heart-fill fs-1 text-primary mb-3" style="font-size:4rem!important;"></i>
                    <h5 class="fw-bold text-dark">Select a conversation</h5>
                    <p class="small">Choose from your messages or start a new chat.</p>
                    <button class="btn btn-primary rounded-pill px-4 mt-2" data-bs-toggle="modal" data-bs-target="#newChatModal">
                        <i class="bi bi-plus-lg me-1"></i> New Conversation
                    </button>
                </div>
            @endisset
        </div>
    </div>
</div>

{{-- Start New Conversation Modal --}}
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-light pb-0">
                <h5 class="modal-title fw-bold">New Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('chat.start') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="form-label fw-semibold">Select Employee</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">— Choose an employee —</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Start Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .conversation-item:hover { background: rgba(79, 70, 229, 0.05) !important; }
    .chat-bubble {
        max-width: 70%;
        padding: 10px 14px;
        border-radius: 18px;
        word-break: break-word;
    }
    .chat-bubble-mine {
        background: #4f46e5;
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    .chat-bubble-other {
        background: #fff;
        color: #1e293b;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    }
    #messageContainer { scroll-behavior: smooth; }
</style>
@endpush

@push('scripts')
<script>
    // Scroll to bottom on load
    const container = document.getElementById('messageContainer');
    if (container) container.scrollTop = container.scrollHeight;

    @isset($conversation)
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const conversationId = {{ $conversation->id }};
    const authId = {{ auth()->id() }};

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const body = messageInput.value.trim();
        if (!body) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        try {
            const res = await fetch('{{ route('chat.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ conversation_id: conversationId, body: body })
            });

            const data = await res.json();
            messageInput.value = '';

            // Append message to chat
            const msgDiv = document.createElement('div');
            msgDiv.className = 'd-flex mb-3 justify-content-end';
            msgDiv.innerHTML = `
                <div class="chat-bubble chat-bubble-mine">
                    <p class="mb-1" style="font-size:0.9rem;">${data.body}</p>
                    <small style="font-size:0.65rem;opacity:0.7;">${data.created_at} &nbsp;<i class="bi bi-check2-all"></i></small>
                </div>
            `;
            container.insertBefore(msgDiv, document.getElementById('messagesEnd'));
            container.scrollTop = container.scrollHeight;

        } catch (err) {
            console.error('Failed to send message:', err);
        }
    });

    // Poll for new messages every 5 seconds
    let lastMsgId = {{ $messages->last()?->id ?? 0 }};

    setInterval(async () => {
        try {
            const res = await fetch(`/api/chat/{{ $conversation->id }}/messages?after=${lastMsgId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            if (!res.ok) return;
            const msgs = await res.json();
            msgs.forEach(msg => {
                if (msg.user_id !== authId) {
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'd-flex mb-3 justify-content-start';
                    msgDiv.dataset.msgId = msg.id;
                    msgDiv.innerHTML = `
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(msg.user_name)}&size=32&background=random" class="rounded-circle me-2 align-self-end" width="32" height="32">
                        <div class="chat-bubble chat-bubble-other">
                            <p class="mb-1" style="font-size:0.9rem;">${msg.body}</p>
                            <small style="font-size:0.65rem;opacity:0.7;">${msg.created_at}</small>
                        </div>
                    `;
                    container.insertBefore(msgDiv, document.getElementById('messagesEnd'));
                    container.scrollTop = container.scrollHeight;
                    lastMsgId = msg.id;
                }
            });
        } catch (e) {}
    }, 5000);
    @endisset

    // Search conversations
    document.getElementById('searchConversation')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    // Notification polling – update bell badge every 30s
    async function pollNotifications() {
        try {
            const res = await fetch('{{ route('notifications.json') }}', { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            const badge = document.getElementById('notif-badge');
            if (badge) {
                badge.textContent = data.unread_count;
                badge.style.display = data.unread_count > 0 ? '' : 'none';
            }
        } catch(e) {}
    }
    pollNotifications();
    setInterval(pollNotifications, 30000);
</script>
@endpush
