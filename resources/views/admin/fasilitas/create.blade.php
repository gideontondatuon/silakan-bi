<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-plus-circle" style="color:#005baa;margin-right:8px;"></i>Tambah Fasilitas</h1>
        <p>Tambahkan fasilitas baru untuk digunakan pada ruangan.</p>
    </div>
    <a href="{{ route('admin.fasilitas.index') }}" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section" style="max-width:480px;">
    <div class="section-header">
        <h2><i class="bi bi-tools"></i> Data Fasilitas</h2>
    </div>
    <div style="padding:24px;">
        <form method="POST" action="{{ route('admin.fasilitas.store') }}">
            @csrf
            <div class="form-group">
                <label>Nama Fasilitas <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama_fasilitas" value="{{ old('nama_fasilitas') }}" placeholder="Contoh: Proyektor, AC, Whiteboard" required autofocus>
                @error('nama_fasilitas') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
            </div>
            <div class="form-action">
                <a href="{{ route('admin.fasilitas.index') }}" class="btn-secondary"><i class="bi bi-x"></i> Batal</a>
                <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>