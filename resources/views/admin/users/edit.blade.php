<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-person-gear" style="color:#005baa;margin-right:8px;"></i>Edit User</h1>
        <p>Perbarui informasi akun pengguna <strong>{{ $user->name ?? $user->username }}</strong>.</p>
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
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label>Username <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}">
                    @error('name') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}">
                    @error('email') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label><i class="bi bi-whatsapp" style="color:#25d366;margin-right:4px;"></i> No. WhatsApp</label>
                    <input type="text" name="no_wa" value="{{ old('no_wa', $user->no_wa) }}" placeholder="Contoh: 081234567890">
                    @error('no_wa') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password Baru <span style="font-weight:400;color:#94a3b8;">(kosongkan jika tidak diubah)</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Isi jika ingin ubah password">
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Unit <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_unit" value="{{ old('nama_unit', $user->nama_unit) }}" required>
                    @error('nama_unit') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Kode Unit <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_unit" value="{{ old('kode_unit', $user->kode_unit) }}" required>
                    @error('kode_unit') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group" style="max-width:320px;">
                <label>Role / Hak Akses <span style="color:#ef4444;">*</span></label>
                <select name="role" required>
                    <option value="user"  {{ old('role', is_object($user->role) ? $user->role->value : $user->role) == 'user'  ? 'selected' : '' }}>User (Pengguna Biasa)</option>
                    <option value="admin" {{ old('role', is_object($user->role) ? $user->role->value : $user->role) == 'admin' ? 'selected' : '' }}>Admin (Administrator)</option>
                </select>
                @error('role') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
            </div>

            <div class="form-action">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <i class="bi bi-x"></i> Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const p = document.getElementById('password');
    const i = document.getElementById('eyeIcon');
    if (p.type === 'password') { p.type = 'text';     i.classList.replace('bi-eye','bi-eye-slash'); }
    else                       { p.type = 'password'; i.classList.replace('bi-eye-slash','bi-eye'); }
}
</script>

</x-app-layout>