<div class="menu-section">
    MENU
</div>


<a href="{{ route('user.dashboard') }}"
   class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">

    <i class="bi bi-grid-fill"></i>

    <span>
        Dashboard
    </span>

</a>


<a href="{{ route('pemesanan.create') }}"
   class="{{ request()->routeIs('pemesanan.create') ? 'active' : '' }}">

    <i class="bi bi-calendar-plus"></i>

    <span>
        Pemesanan
    </span>

</a>


<a href="{{ route('kalender.index') }}"
   class="{{ request()->routeIs('kalender.*') ? 'active' : '' }}">

    <i class="bi bi-calendar3"></i>

    <span>
        Kalender Ruangan
    </span>

</a>


<a href="{{ route('pemesanan.index') }}"
   class="{{ request()->routeIs('pemesanan.index', 'pemesanan.show') ? 'active' : '' }}">

    <i class="bi bi-clock-history"></i>

    <span>
        Riwayat
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


<a href="{{ route('profile.edit') }}"
   class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">

    <i class="bi bi-person-circle"></i>

    <span>
        Profil
    </span>

</a>