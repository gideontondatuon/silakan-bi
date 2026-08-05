<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-person-plus" style="color:#005baa;margin-right:8px;"></i>Tambah User</h1>
        <p>Tambahkan pengguna baru ke dalam sistem SILAKAN.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-person"></i> Informasi Akun</h2>
    </div>

    <div style="padding:24px;">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Username <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                    @error('username') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                    @error('name') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com">
                    @error('email') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label><i class="bi bi-whatsapp" style="color:#25d366;margin-right:4px;"></i> No. WhatsApp</label>
                    <input type="text" name="no_wa" value="{{ old('no_wa') }}" placeholder="Contoh: 081234567890">
                    @error('no_wa') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password <span style="color:#ef4444;">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Buat password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Unit <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_unit" value="{{ old('nama_unit') }}" placeholder="Contoh: Departemen Keuangan" required>
                    @error('nama_unit') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Kode Unit <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_unit" value="{{ old('kode_unit') }}" placeholder="Contoh: KEUANGAN" required>
                    @error('kode_unit') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group" style="max-width:320px;">
                <label>Role / Hak Akses <span style="color:#ef4444;">*</span></label>
                <select name="role" required>
                    <option value="user"  {{ old('role') == 'user'  ? 'selected' : '' }}>User (Pengguna Biasa)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Administrator)</option>
                </select>
                @error('role') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
            </div>

            <div class="form-action">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <i class="bi bi-x"></i> Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan User
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

</x-app-layout>