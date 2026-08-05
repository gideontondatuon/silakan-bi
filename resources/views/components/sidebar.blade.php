<aside class="sidebar" id="sidebar">

    {{-- Fixed Sidebar Brand (Seamlessly aligned with Top Navbar) --}}
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo-bi2.png') }}"
             class="sidebar-logo"
             alt="Bank Indonesia">

        <div class="sidebar-brand-text">
            <strong>SILAKAN</strong>
            <span>Sistem Informasi Layanan Kantor</span>
            <small>KPwBI Prov. Sulut</small>
        </div>
    </div>

    {{-- Scrollable Sidebar Menu --}}
    <nav class="sidebar-menu">

        @if(auth()->user()->role->value === 'admin')
            @include('components.sidebar.admin')
        @else
            @include('components.sidebar.user')
        @endif

    </nav>

    <div class="sidebar-footer">

        <a href="{{ route('profile.edit') }}" class="user-info-link" title="Lihat Profil">
            <div class="user-info">
                <div class="user-avatar-initials" style="{{ auth()->user()->avatar_style }}">
                    {{ auth()->user()->initials }}
                </div>
                <div class="user-info-text">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ auth()->user()->role->label() }}</small>
                </div>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </form>

    </div>

</aside>