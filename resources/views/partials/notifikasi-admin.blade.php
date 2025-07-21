@php
  $notifCount = $notifications->count();
@endphp

<a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-toggle="dropdown"
  aria-haspopup="true" aria-expanded="false" style="font-size: 28px; margin-top: 4px;">
  <i class="fa-solid fa-bell"></i>
  @if($notifCount > 0)
    <span class="badge badge-danger position-absolute" style="top: 0; right: 0; font-size: 10px;">
      {{ $notifCount }}
    </span>
  @endif
</a>

<div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifDropdown">
  @if($notifCount > 0)
    @foreach($notifications as $notif)
      <a class="dropdown-item" href="{{ route('admin-transaction-detail', $notif->data['transaction_id']) }}">
        <strong>{{ $notif->data['title'] }}</strong><br>
        {{ $notif->data['message'] }}
      </a>
    @endforeach
  @else
    <span class="dropdown-item text-muted">Tidak ada notifikasi baru</span>
  @endif
</div>
