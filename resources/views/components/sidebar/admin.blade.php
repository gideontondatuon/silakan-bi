<div class="menu-section">
    UTAMA
</div>

<a href="{{ route('admin.dashboard') }}"
   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

    <i class="bi bi-grid-fill"></i>

    <span>
        Dashboard
    </span>

</a>


<a href="{{ route('admin.approval.index') }}"
   class="{{ request()->routeIs('admin.approval.*') ? 'active' : '' }}">

    <i class="bi bi-clock-history"></i>

    <span>
        Waiting List
    </span>

</a>


<a href="{{ route('admin.kegiatan-berlangsung.index') }}"
   class="{{ request()->routeIs('admin.kegiatan-berlangsung.*') ? 'active' : '' }}">

    <i class="bi bi-play-circle"></i>

    <span>
        Kegiatan Berlangsung
    </span>

</a>


<a href="{{ route('kalender.index') }}"
   class="{{ request()->routeIs('kalender.*') ? 'active' : '' }}">

    <i class="bi bi-calendar3"></i>

    <span>
        Kalender Ruangan
    </span>

</a>


<a href="{{ route('notifications.index') }}"
   class="{{ request()->routeIs('notifications.*', 'notification.*') ? 'active' : '' }}">

    <i class="bi bi-bell"></i>

    <span>
        Notifikasi
    </span>

    @php
        $unreadNotification = auth()
            ->user()
            ->unreadNotifications()
            ->count();
    @endphp

    @if($unreadNotification > 0)
        <small class="sidebar-badge">
            {{ $unreadNotification }}
        </small>
    @endif

</a>


<div class="menu-section">
    MASTER <br> DATA
</div>


<a href="{{ route('admin.ruangan.index') }}"
   class="{{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">

    <i class="bi bi-building"></i>

    <span>
        Data Ruangan
    </span>

</a>

<a href="{{ route('admin.layout.index') }}"
   class="{{ request()->routeIs('admin.layout.*') ? 'active' : '' }}">

    <i class="bi bi-layout-text-sidebar-reverse"></i>

    <span>
        Data Layout
    </span>

</a>


<a href="{{ route('admin.hari-libur.index') }}"
   class="{{ request()->routeIs('admin.hari-libur.*') ? 'active' : '' }}">

    <i class="bi bi-calendar2-week"></i>

    <span>
        Hari Libur
    </span>

</a>


<a href="{{ route('admin.users.index') }}"
   class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

    <i class="bi bi-people"></i>

    <span>
        Data User
    </span>

</a>


<div class="menu-section">
    SISTEM
</div>


<a href="{{ route('admin.laporan.index') }}"
   class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">

    <i class="bi bi-file-earmark-bar-graph"></i>

    <span>
        Laporan
    </span>

</a>


<a href="{{ route('admin.audit-log.index') }}"
   class="{{ request()->routeIs('admin.audit-log.*') ? 'active' : '' }}">

    <i class="bi bi-journal-text"></i>

    <span>
        Audit Log
    </span>

</a>