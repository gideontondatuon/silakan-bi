<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-pencil-square" style="color:#005baa;margin-right:8px;"></i>Edit Fasilitas</h1>
        <p>Perbarui nama fasilitas <strong>{{ $fasilitas->nama_fasilitas }}</strong>.</p>
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
        <form method="POST" action="{{ route('admin.fasilitas.update', $fasilitas) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Fasilitas <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama_fasilitas" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
                @error('nama_fasilitas') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
            </div>
            <div class="form-action">
                <a href="{{ route('admin.fasilitas.index') }}" class="btn-secondary"><i class="bi bi-x"></i> Batal</a>
                <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>