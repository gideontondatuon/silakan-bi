<x-guest-layout>

<div class="login-card">

    {{-- Brand Header (PRIMARY HIGHLIGHT) --}}
    <div class="login-brand" style="text-align:center;margin-bottom:24px;display:flex;flex-direction:column;align-items:center;">
        {{-- Bank Indonesia Logo --}}
        <img src="{{ asset('images/logo-bi4.png') }}"
             class="login-logo-bi"
             alt="Bank Indonesia"
             style="height:62px;width:auto;max-width:280px;object-fit:contain;margin-bottom:14px;filter:drop-shadow(0 3px 8px rgba(0,91,170,0.12));">

        {{-- Accent Line Divider --}}
        <div style="height:2px;width:60px;background:linear-gradient(90deg, #005baa, #b8972a);border-radius:2px;margin-bottom:14px;"></div>

        {{-- System Title (DOMINANT HIGHLIGHT) --}}
        <div class="login-brand-text">
            <h1 style="font-size:32px;font-weight:800;color:#003b73;letter-spacing:4px;margin:0 0 6px 0;line-height:1;">SILAKAN</h1>
            <p style="font-size:13.5px;font-weight:600;color:#475569;margin:0;line-height:1.4;letter-spacing:0.3px;">Sistem Informasi Layanan Kantor<br><span style="color:#005baa;font-weight:700;font-size:14px;">KPwBI Provinsi Sulawesi Utara</span></p>
        </div>
    </div>

    {{-- Section Header (SECONDARY / SUBTLE) --}}
    <h2 class="login-title">MASUK KE SISTEM</h2>
    <p class="login-subtitle">Silakan masukkan akun terdaftar Anda untuk melanjutkan</p>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Username / Email --}}
        <div class="login-group">
            <label for="login_input">
                <i class="bi bi-person" style="margin-right:5px;"></i>
                Username / Email
            </label>
            <input
                id="login_input"
                class="login-input {{ $errors->has('login_input') ? 'border-danger' : '' }}"
                type="text"
                name="login_input"
                value="{{ old('login_input') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan username atau email"
            >
            <x-input-error :messages="$errors->get('login_input')" />
        </div>

        {{-- Password --}}
        <div class="login-group">
            <label for="password">
                <i class="bi bi-lock" style="margin-right:5px;"></i>
                Password
            </label>
            <div class="password-wrapper">
                <input
                    id="password"
                    class="login-input {{ $errors->has('password') ? 'border-danger' : '' }}"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                >
                <button type="button" class="toggle-password" onclick="togglePassword()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        {{-- Remember Me --}}
        <label class="login-remember">
            <input type="checkbox" name="remember">
            Ingat saya di perangkat ini
        </label>

        {{-- Submit --}}
        <button type="submit" class="login-button" id="login-btn">
            <i class="bi bi-box-arrow-in-right"></i>
            Masuk
        </button>

    </form>

    {{-- Footer --}}
    <div class="login-footer">
        <i class="bi bi-bank" style="font-size:16px;color:#005baa;"></i><br>
        &copy; {{ date('Y') }} Bank Indonesia<br>
        KPwBI Provinsi Sulawesi Utara
    </div>

</div>


<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon     = document.getElementById('eyeIcon');
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

/* Loading state on submit */
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('login-btn');
    if (btn) {
        btn.innerHTML = '<span style="width:16px;height:16px;border:2px solid rgba(255,255,255,0.4);border-top-color:white;border-radius:50%;animation:spin 0.8s linear infinite;display:inline-block;"></span> Memproses...';
        btn.disabled = true;
    }
});
</script>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
.border-danger { border-color: #ef4444 !important; }
</style>

</x-guest-layout>