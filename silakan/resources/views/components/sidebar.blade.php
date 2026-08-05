<aside class="sidebar">

    <div class="sidebar-brand">
        <div class="brand-title">
            SILAKAN
        </div>

        <div class="brand-subtitle">
            Sistem Informasi
            <br>
            Layanan Kantor
        </div>
    </div>


    <nav class="sidebar-menu">

        @if(auth()->user()->role->value === 'admin')

            @include('components.sidebar.admin')

        @else

            @include('components.sidebar.user')

        @endif

    </nav>


    <div class="sidebar-footer">

        <div class="user-info">

            <strong>
                {{ auth()->user()->name }}
            </strong>

            <small>
                {{ auth()->user()->role->label() }}
            </small>

        </div>


        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button type="submit" class="logout-button">
                Logout
            </button>

        </form>

    </div>

</aside>