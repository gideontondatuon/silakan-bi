<x-app-layout>

<style>
@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-person-badge-fill" style="color:#005baa;margin-right:8px;"></i>Profil Saya</h1>
        <p>Kelola informasi akun dan pengaturan keamanan profil Anda.</p>
    </div>
</div>

<div style="display:flex;flex-direction:column;gap:24px;max-width:900px;">

    {{-- User Header Banner --}}
    <div style="background:linear-gradient(135deg, #003b73 0%, #005baa 100%);border-radius:16px;padding:28px 32px;color:white;box-shadow:0 10px 25px -5px rgba(0,91,170,0.25);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
        <div style="display:flex;align-items:center;gap:20px;">
            <div style="width:72px;height:72px;border-radius:50%;background:white;color:#003b73;display:flex;align-items:center;justify-content:center;font-weight:800;box-shadow:0 4px 12px rgba(0,0,0,0.15);flex-shrink:0;{{ $user->avatar_style }}">
                <span style="font-size:1.4em;">{{ $user->initials }}</span>
            </div>
            <div>
                <h2 style="font-size:22px;font-weight:800;margin:0 0 6px 0;letter-spacing:-0.3px;color:#ffffff;">{{ $user->name }}</h2>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <span style="background:rgba(255,255,255,0.2);backdrop-filter:blur(4px);padding:4px 12px;border-radius:9999px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-shield-check"></i> {{ $user->role->label() }}
                    </span>
                    @if($user->kode_unit)
                    <span style="background:rgba(255,255,255,0.2);backdrop-filter:blur(4px);padding:4px 12px;border-radius:9999px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-tag-fill"></i> Kode Unit: {{ $user->kode_unit }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div style="text-align:right;">
            <span style="display:block;font-size:12px;opacity:0.85;">Username Akun</span>
            <strong style="font-size:16px;font-family:monospace;letter-spacing:0.5px;">{{ $user->username }}</strong>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;" class="profile-grid">
        
        {{-- Profile Info Card --}}
        <div class="dashboard-section" style="margin:0;background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.05);border:1px solid #e2e8f0;overflow:hidden;">
            <div class="section-header" style="padding:18px 24px;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-person-vcard" style="color:#005baa;"></i> Informasi Akun
                </h2>
            </div>
            <div style="padding:24px;">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Password Card --}}
        <div class="dashboard-section" style="margin:0;background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.05);border:1px solid #e2e8f0;overflow:hidden;">
            <div class="section-header" style="padding:18px 24px;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-shield-lock-fill" style="color:#005baa;"></i> Keamanan &amp; Password
                </h2>
            </div>
            <div style="padding:24px;">
                @include('profile.partials.update-password-form')
            </div>
        </div>

    </div>

</div>

</x-app-layout>
