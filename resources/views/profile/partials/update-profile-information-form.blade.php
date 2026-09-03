@php
    $isAdmin = auth()->user()->role->value === 'admin';
@endphp

@if($isAdmin)
    <form method="post" action="{{ route('profile.update') }}" style="display:flex;flex-direction:column;gap:18px;">
        @csrf
        @method('patch')

        {{-- Nama Administrator --}}
        <div class="form-group" style="margin-bottom:0;">
            <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
                Nama Administrator
            </label>
            <div style="position:relative;">
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%;padding:10px 14px 10px 42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;font-weight:600;" placeholder="Masukkan Nama Administrator">
                <i class="bi bi-person-fill" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
            </div>
            @error('name')
                <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        {{-- Username ID --}}
        <div class="form-group" style="margin-bottom:0;">
            <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
                Username ID
            </label>
            <div style="position:relative;">
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required style="width:100%;padding:10px 14px 10px 42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;font-weight:600;" placeholder="Masukkan Username Admin">
                <i class="bi bi-person-badge" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
            </div>
            @error('username')
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
@else
    {{-- Tampilan Informasi Akun Unit Kerja (Read-Only) --}}
    <div style="display:flex;flex-direction:column;gap:18px;">
        {{-- Nama Unit --}}
        <div class="form-group" style="margin-bottom:0;">
            <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
                Nama Unit / Pengguna
                <span style="font-size:11px;font-weight:600;color:#dc2626;margin-left:6px;"><i class="bi bi-lock-fill"></i> (Terkunci)</span>
            </label>
            <div style="position:relative;">
                <input type="text" value="{{ $user->name }}" readonly disabled style="width:100%;padding:10px 14px 10px 42px !important;border:1px solid #cbd5e1;border-radius:10px;background:#f8fafc;color:#1e293b;font-weight:700;font-size:13.5px;cursor:not-allowed;">
                <i class="bi bi-building" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
            </div>
            <span style="display:block;font-size:11.5px;color:#64748b;margin-top:6px;line-height:1.4;">
                <i class="bi bi-info-circle" style="color:#005baa;"></i> Nama unit dikunci oleh sistem. Hubungi Administrator jika terdapat penyesuaian nama unit.
            </span>
        </div>

        {{-- Username ID --}}
        <div class="form-group" style="margin-bottom:0;">
            <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
                Username ID
                <span style="font-size:11px;font-weight:600;color:#dc2626;margin-left:6px;"><i class="bi bi-lock-fill"></i> (Terkunci)</span>
            </label>
            <div style="position:relative;">
                <input type="text" value="{{ $user->username }}" readonly disabled style="width:100%;padding:10px 14px 10px 42px !important;border:1px solid #cbd5e1;border-radius:10px;background:#f8fafc;color:#1e293b;font-weight:700;font-size:13.5px;font-family:monospace;cursor:not-allowed;">
                <i class="bi bi-person-badge" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
            </div>
        </div>

        @if($user->kode_unit)
        {{-- Kode Unit --}}
        <div class="form-group" style="margin-bottom:0;">
            <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
                Kode Unit Kerja
            </label>
            <div style="position:relative;">
                <input type="text" value="{{ $user->kode_unit }}" readonly disabled style="width:100%;padding:10px 14px 10px 42px !important;border:1px solid #cbd5e1;border-radius:10px;background:#f8fafc;color:#1e293b;font-weight:700;font-size:13.5px;cursor:not-allowed;">
                <i class="bi bi-tag" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
            </div>
        </div>
        @endif

        {{-- Role / Hak Akses --}}
        <div class="form-group" style="margin-bottom:0;">
            <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
                Hak Akses / Peran
            </label>
            <div style="position:relative;">
                <input type="text" value="Pegawai Unit Kerja (Pemohon Ruangan)" readonly disabled style="width:100%;padding:10px 14px 10px 42px !important;border:1px solid #cbd5e1;border-radius:10px;background:#f8fafc;color:#1e293b;font-weight:700;font-size:13.5px;cursor:not-allowed;">
                <i class="bi bi-shield-check" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#059669;font-size:15px;"></i>
            </div>
        </div>

        <div style="padding:12px 14px;border-radius:10px;background:#f0f9ff;border:1px solid #bae6fd;font-size:12px;color:#0369a1;line-height:1.45;display:flex;align-items:flex-start;gap:8px;">
            <i class="bi bi-info-circle-fill" style="font-size:15px;color:#0284c7;flex-shrink:0;margin-top:1px;"></i>
            <span>Akun unit kerja dikelola secara terpusat oleh Administrator Silakan. Untuk keamanan, Anda dapat memperbarui kata sandi secara berkala pada kartu <strong>Keamanan &amp; Password</strong> di samping.</span>
        </div>
    </div>
@endif
