<form method="post" action="{{ route('password.update') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf
    @method('put')

    {{-- Password Saat Ini --}}
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;" class="required">Password Saat Ini</label>
        <div style="position:relative;">
            <input type="password" name="current_password" required autocomplete="current-password" style="width:100%;padding:10px 14px 10px 38px;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;" placeholder="Masukkan password saat ini">
            <i class="bi bi-key" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        @error('current_password', 'updatePassword')
            <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    {{-- Password Baru --}}
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;" class="required">Password Baru</label>
        <div style="position:relative;">
            <input type="password" name="password" required autocomplete="new-password" style="width:100%;padding:10px 14px 10px 38px;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;" placeholder="Minimal 8 karakter">
            <i class="bi bi-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        @error('password', 'updatePassword')
            <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;" class="required">Konfirmasi Password Baru</label>
        <div style="position:relative;">
            <input type="password" name="password_confirmation" required autocomplete="new-password" style="width:100%;padding:10px 14px 10px 38px;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;" placeholder="Ulangi password baru">
            <i class="bi bi-shield-check" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        @error('password_confirmation', 'updatePassword')
            <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    {{-- Submit Button & Notification --}}
    <div style="display:flex;align-items:center;gap:12px;margin-top:6px;flex-wrap:wrap;">
        <button type="submit" class="btn-primary" style="padding:10px 20px;font-weight:600;display:inline-flex;align-items:center;gap:6px;background:#005baa;border-radius:10px;">
            <i class="bi bi-shield-lock"></i> Perbarui Password
        </button>

        @if (session('status') === 'password-updated')
            <span style="color:#059669;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                <i class="bi bi-check-circle-fill"></i> Password berhasil diubah.
            </span>
        @endif
    </div>
</form>
