<x-app-layout>

@php
    $isAdminAccount = (is_object($user->role) ? $user->role->value : $user->role) === 'admin';
@endphp

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
                    <label>Nama Lengkap / Administrator @if($isAdminAccount)<span style="color:#005baa;font-size:11px;">(Dapat Anda sesuaikan)</span>@endif</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan Nama Pengguna / Administrator">
                    @error('name') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            @php
                $currentPlain = !empty($user->password_plain) ? $user->password_plain : 'kpwbisulut';
            @endphp
            <div class="form-row">
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-key-fill" style="color:#005baa;"></i> Password Akun Saat Ini
                    </label>
                    <div style="display:flex;align-items:center;gap:10px;background:#f8fafc;border:1px solid #cbd5e1;border-radius:10px;padding:10px 14px;max-width:400px;">
                        <span id="currentPassDisplay" style="font-family:Consolas,monospace;font-weight:700;letter-spacing:1px;font-size:14px;color:#1e293b;" data-plain="{{ $currentPlain }}">••••••••</span>
                        <button type="button" onclick="toggleCurrentPass()" style="background:none;border:none;cursor:pointer;color:#005baa;margin-left:auto;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:5px;">
                            <i class="bi bi-eye" id="currentPassIcon"></i> <span id="currentPassLabel">Lihat</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password Baru <span style="font-weight:400;color:#94a3b8;">(kosongkan jika tidak ingin mengubah password)</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Ketik password baru (minimal 8 karakter)">
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Unit @if(!$isAdminAccount)<span style="color:#ef4444;">*</span>@endif</label>
                    <input type="text" name="nama_unit" value="{{ old('nama_unit', $user->nama_unit ?? 'Administrator') }}" {{ !$isAdminAccount ? 'required' : '' }}>
                    @error('nama_unit') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Kode Unit @if(!$isAdminAccount)<span style="color:#ef4444;">*</span>@endif</label>
                    <input type="text" name="kode_unit" value="{{ old('kode_unit', $user->kode_unit ?? 'ADM') }}" {{ !$isAdminAccount ? 'required' : '' }}>
                    @error('kode_unit') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group" style="max-width:340px;">
                <label style="display:flex;align-items:center;gap:6px;">
                    Role / Hak Akses
                    @if($isAdminAccount)
                        <span style="font-size:11px;font-weight:600;color:#dc2626;"><i class="bi bi-lock-fill"></i> (Terkunci)</span>
                    @else
                        <span style="color:#ef4444;">*</span>
                    @endif
                </label>
                @if($isAdminAccount)
                    <input type="hidden" name="role" value="admin">
                    <div style="position:relative;">
                        <input type="text" value="Admin (Administrator)" readonly disabled style="background:#f1f5f9;color:#475569;font-weight:700;cursor:not-allowed;border:1px solid #cbd5e1;border-radius:10px;padding:10px 14px 10px 42px !important;width:100%;font-size:13.5px;">
                        <i class="bi bi-shield-lock-fill" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
                    </div>
                    <span style="display:block;font-size:11.5px;color:#64748b;margin-top:5px;line-height:1.4;">
                        <i class="bi bi-info-circle" style="color:#005baa;"></i> Role akun Administrator dikunci permanen oleh sistem demi keamanan.
                    </span>
                @else
                    <select name="role" required>
                        <option value="user"  {{ old('role', is_object($user->role) ? $user->role->value : $user->role) == 'user'  ? 'selected' : '' }}>User (Pengguna Biasa)</option>
                        <option value="admin" {{ old('role', is_object($user->role) ? $user->role->value : $user->role) == 'admin' ? 'selected' : '' }}>Admin (Administrator)</option>
                    </select>
                    @error('role') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                @endif
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
    if (p.type === 'password') { 
        p.type = 'text';     
        i.classList.replace('bi-eye','bi-eye-slash'); 
    } else { 
        p.type = 'password'; 
        i.classList.replace('bi-eye-slash','bi-eye'); 
    }
}

function toggleCurrentPass() {
    const disp = document.getElementById('currentPassDisplay');
    const icon = document.getElementById('currentPassIcon');
    const label = document.getElementById('currentPassLabel');
    if (!disp) return;

    const plain = disp.getAttribute('data-plain');
    if (disp.innerText === '••••••••') {
        disp.innerText = plain;
        icon.classList.replace('bi-eye', 'bi-eye-slash');
        label.innerText = 'Sembunyikan';
    } else {
        disp.innerText = '••••••••';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
        label.innerText = 'Lihat';
    }
}
</script>

</x-app-layout>