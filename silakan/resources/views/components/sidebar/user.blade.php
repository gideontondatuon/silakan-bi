<div class="menu-section">
    MENU
</div>


<a href="{{ route('dashboard') }}"
   class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

    Dashboard

</a>


<a href="{{ route('pemesanan.create') }}">
    Pemesanan
</a>


<a href="#">
    Kalender Ruangan
</a>


<a href="{{ route('pemesanan.index') }}">
    Riwayat
</a>

<a href="{{ route('kalender.index') }}">

    Kalender Ruangan

</a>


<a href="#">
    Notifikasi
</a>


<a href="#">
    Profil
</a>