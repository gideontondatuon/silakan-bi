<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-bell-fill" style="color:#005baa;margin-right:8px;"></i>Notifikasi</h1>
        <p>Riwayat informasi dan aktivitas pemesanan ruangan Anda.</p>
    </div>
    @if(isset($notifications) && $notifications->count() > 0)
    <div style="display:flex;gap:10px;align-items:center;">
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit" class="btn-secondary">
                <i class="bi bi-check-all" style="font-size:18px;"></i> Tandai Semua Dibaca
            </button>
        </form>
        <form method="POST" action="{{ route('notifications.destroyAll') }}" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Semua Notifikasi', message: 'Apakah Anda yakin ingin menghapus seluruh riwayat notifikasi?', type: 'danger', confirmText: 'Ya, Hapus Semua' })">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-secondary" style="color:#dc2626;border-color:#fecdd3;background:#fff1f2;">
                <i class="bi bi-trash-fill" style="color:#dc2626;"></i> Hapus Semua
            </button>
        </form>
    </div>
    @endif
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-inbox-fill"></i> Daftar Notifikasi</h2>
        @if(isset($notifications) && $notifications->total() > 0)
        <span class="badge badge-primary">{{ $notifications->total() }} Notifikasi</span>
        @endif
    </div>

    <div class="notification-list">
        @forelse($notifications as $notification)
        <div class="notification-card-wrapper" style="position:relative;margin-bottom:12px;">
            <a href="{{ route('notification.read', $notification->id) }}"
               class="notification-card {{ !$notification->read_at ? 'unread' : 'read' }}" style="padding-right:60px;">
                <div class="notification-card-icon">
                    @if(str_contains(strtolower($notification->data['judul'] ?? ''), 'disetujui'))
                        <i class="bi bi-check-circle-fill" style="color:#10b981;"></i>
                    @elseif(str_contains(strtolower($notification->data['judul'] ?? ''), 'ditolak'))
                        <i class="bi bi-x-circle-fill" style="color:#ef4444;"></i>
                    @else
                        <i class="bi bi-bell-fill" style="color:#005baa;"></i>
                    @endif
                </div>

                <div class="notification-card-body">
                    <div class="notification-card-top">
                        <strong class="notification-card-title">{{ $notification->data['judul'] ?? 'Notifikasi' }}</strong>
                        @if(!$notification->read_at)
                            <span class="badge badge-warning"><i class="bi bi-circle-fill" style="font-size:7px;"></i> Baru</span>
                        @else
                            <span class="badge badge-secondary"><i class="bi bi-check2"></i> Dibaca</span>
                        @endif
                    </div>
                    <p class="notification-card-message">{{ $notification->data['pesan'] ?? '' }}</p>
                    <small class="notification-card-time">
                        <i class="bi bi-clock"></i> {{ $notification->data['waktu'] ?? $notification->created_at->diffForHumans() }}
                    </small>
                </div>
            </a>

            {{-- Single Delete Action --}}
            <form action="{{ route('notification.destroy', $notification->id) }}" method="POST" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);z-index:10;" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Notifikasi', message: 'Apakah Anda yakin ingin menghapus notifikasi ini?', type: 'danger', confirmText: 'Hapus' })">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:8px;border-radius:8px;transition:all 0.2s ease;" onmouseover="this.style.color='#ef4444';this.style.background='#fff1f2';" onmouseout="this.style.color='#94a3b8';this.style.background='none';" title="Hapus Notifikasi Ini">
                    <i class="bi bi-trash-fill" style="font-size:16px;"></i>
                </button>
            </form>
        </div>
        @empty
        <div class="empty-state">
            <i class="bi bi-bell-slash"></i>
            <p>Belum ada notifikasi.</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

</x-app-layout>