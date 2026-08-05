<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-plus-circle" style="color:#005baa;margin-right:8px;"></i>Tambah Layout Ruangan</h1>
        <p>Tambahkan variasi layout baru pada sistem SILAKAN.</p>
    </div>
    <a href="{{ route('admin.layout.index') }}" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section" style="max-width:480px;">
    <div class="section-header">
        <h2><i class="bi bi-layout-wtf"></i> Data Layout</h2>
    </div>
    <div style="padding:24px;">
        <form method="POST" action="{{ route('admin.layout.store') }}">
            @csrf
            <div class="form-group">
                <label>Nama Layout <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama_layout" value="{{ old('nama_layout') }}" placeholder="Contoh: Theater, Classroom, U-Shape" required autofocus>
                @error('nama_layout') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
            </div>
            <div class="form-action">
                <a href="{{ route('admin.layout.index') }}" class="btn-secondary"><i class="bi bi-x"></i> Batal</a>
                <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>