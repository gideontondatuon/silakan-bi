<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-people" style="color:#005baa;margin-right:8px;"></i>Data User</h1>
        <p>Manajemen akun pengguna sistem SILAKAN.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <i class="bi bi-person-plus"></i> Tambah User
    </a>
</div>


<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Unit Kerja</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#005baa,#003b73);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;flex-shrink:0;{{ $user->avatar_style }}">
                                {{ $user->initials }}
                            </div>
                            <strong style="color:#003b73;">{{ $user->username }}</strong>
                        </div>
                    </td>
                    <td>{{ $user->name ?? '-' }}</td>
                    <td>
                        @if($user->nama_unit)
                            <span style="display:flex;align-items:center;gap:5px;color:#475569;">
                                <i class="bi bi-briefcase" style="color:#005baa;font-size:12px;"></i>
                                {{ $user->nama_unit }}
                            </span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($user->role->value === 'admin')
                            <span class="badge badge-primary"><i class="bi bi-shield-check"></i> Admin</span>
                        @else
                            <span class="badge badge-secondary"><i class="bi bi-person"></i> User</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus User', message: 'Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong> ({{ $user->username }})?', type: 'danger', confirmText: 'Ya, Hapus' })">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>Belum ada data user. <a href="{{ route('admin.users.create') }}">Tambah user sekarang</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        {{ $users->links() }}
    </div>
    @endif
</div>

</x-app-layout>