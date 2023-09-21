<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Notifications @if($unreadCount)<span class="badge badge-info">{{ $unreadCount }}</span>@endif
    </button>
    <ul class="dropdown-menu">
        @foreach($notifications as $notification)
            <li>
                <a href="{{ $notification->data['link'] . '?nid='. $notification->id }}">
                    @if($notification->unread()) <b>*</b>@endif
                    {{ $notification->data['content'] }}
                    <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans(null,true,true) }}</small>
                </a>
            </li>
        @endforeach
    </ul>
</div>
