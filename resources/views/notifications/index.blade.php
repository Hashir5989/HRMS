@extends('layouts.admin')

@section('title', 'Notifications - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-bell-fill me-2 text-primary"></i>Notifications</h3>
            <p class="text-muted mb-0">Stay updated with all activity across HRMS</p>
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.markAllRead') }}" method="POST">
            @csrf
            <button class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-check2-all me-1"></i> Mark All as Read
            </button>
        </form>
        @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            @forelse($notifications as $notif)
                @php
                    $data = $notif->data;
                    $isRead = !is_null($notif->read_at);
                @endphp
                <div class="card border-0 shadow-sm mb-3 {{ $isRead ? '' : 'border-start border-primary border-3' }}" style="{{ $isRead ? '' : 'border-left: 4px solid #4f46e5 !important;' }}">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
                            {{ $isRead ? 'bg-light text-muted' : 'bg-primary-subtle text-primary' }}"
                            style="width:44px;height:44px;">
                            @if(($data['type'] ?? '') === 'message')
                                <i class="bi bi-chat-fill"></i>
                            @else
                                <i class="bi bi-bell-fill"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    @if(($data['type'] ?? '') === 'message')
                                        <p class="mb-0 {{ $isRead ? '' : 'fw-semibold' }}">
                                            <span class="text-primary">{{ $data['sender'] ?? 'Someone' }}</span> sent you a message
                                        </p>
                                        <small class="text-muted fst-italic">"{{ Str::limit($data['message'] ?? '', 80) }}"</small>
                                    @else
                                        <p class="mb-0">{{ $data['message'] ?? 'New notification' }}</p>
                                    @endif
                                </div>
                                <small class="text-muted ms-3 text-nowrap">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @if(isset($data['link']))
                        <a href="{{ $data['link'] }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                        @endif
                        @if(!$isRead)
                        <span class="badge bg-primary rounded-pill">New</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-bell-slash fs-1 d-block mb-3 text-primary"></i>
                    <h5>You're all caught up!</h5>
                    <p>No notifications at the moment.</p>
                </div>
            @endforelse

            <div class="mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</div>
@endsection
