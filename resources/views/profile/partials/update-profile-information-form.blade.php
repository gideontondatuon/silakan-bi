<form method="post" action="{{ route('profile.update') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf
    @method('patch')

    @php
        $isAdmin = auth()->user()->role->value === 'admin';
    @endphp

    {{-- Nama Unit / Pengguna --}}
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
            Nama Unit / Pengguna
            @if(!$isAdmin)
                <span style="font-size:11px;font-weight:600;color:#dc2626;margin-left:6px;"><i class="bi bi-lock-fill"></i> (Terkunci)</span>
            @endif
        </label>
        <div style="position:relative;">
            @if($isAdmin)
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;font-weight:600;" placeholder="Masukkan Nama Administrator">
            @else
                <input type="text" value="{{ $user->name }}" readonly disabled style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;background:#f1f5f9;color:#475569;font-weight:600;font-size:13.5px;cursor:not-allowed;">
            @endif
            <i class="bi bi-building" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        @if($isAdmin)
            @error('name')
                <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        @else
            <span style="display:block;font-size:11.5px;color:#64748b;margin-top:6px;line-height:1.4;">
                <i class="bi bi-info-circle" style="color:#005baa;"></i> Nama unit dikunci oleh sistem. Hubungi Administrator jika terdapat penyesuaian nama unit.
            </span>
        @endif
    </div>

    {{-- Username ID --}}
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
            Username ID
            @if(!$isAdmin)
                <span style="font-size:11px;font-weight:600;color:#dc2626;margin-left:6px;"><i class="bi bi-lock-fill"></i> (Terkunci)</span>
            @endif
        </label>
        <div style="position:relative;">
            @if($isAdmin)
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;font-weight:600;" placeholder="Masukkan Username Admin">
            @else
                <input type="text" value="{{ $user->username }}" readonly disabled style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;background:#f1f5f9;color:#475569;font-weight:600;font-size:13.5px;cursor:not-allowed;">
            @endif
            <i class="bi bi-person-badge" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        @if($isAdmin)
            @error('username')
                <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        @endif
    </div>

    {{-- Email --}}
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">Alamat Email <small style="color:#64748b;font-weight:400;">(Opsional)</small></label>
        <div style="position:relative;">
            <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;" placeholder="nama@bi.go.id (opsional)">
            <i class="bi bi-envelope" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        @error('email')
            <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    {{-- Nomor WhatsApp --}}
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">Nomor WhatsApp</label>
        <div style="position:relative;">
            <input type="text" name="no_wa" value="{{ old('no_wa', $user->no_wa) }}" style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;" placeholder="Contoh: 081340693458">
            <i class="bi bi-whatsapp" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#25d366;font-size:15px;"></i>
        </div>
        <span style="display:block;font-size:11.5px;color:#64748b;margin-top:5px;">Menerima notifikasi WhatsApp resmi pengajuan &amp; status pemesanan.</span>
        @error('no_wa')
            <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    {{-- Submit Button & Notification --}}
    <div style="display:flex;align-items:center;gap:12px;margin-top:6px;flex-wrap:wrap;">
        <button type="submit" class="btn-primary" style="padding:10px 20px;font-weight:600;display:inline-flex;align-items:center;gap:6px;border-radius:10px;">
            <i class="bi bi-check2-circle"></i> Simpan Perubahan
        </button>

        @if (session('status') === 'profile-updated')
            <span style="color:#059669;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                <i class="bi bi-check-circle-fill"></i> Profil berhasil diperbarui.
            </span>
        @endif
    </div>
</form>
