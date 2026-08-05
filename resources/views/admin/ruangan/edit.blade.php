<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-pencil-square" style="color:#005baa;margin-right:8px;"></i>Edit Ruangan</h1>
        <p>Perbarui informasi ruangan <strong>{{ $ruangan->nama_ruangan }}</strong>.</p>
    </div>
    <a href="{{ route('admin.ruangan.index') }}" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-building"></i> Informasi Ruangan</h2>
    </div>

    <div style="padding:24px;">
        <form method="POST" action="{{ route('admin.ruangan.update', $ruangan) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Ruangan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_ruangan" value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" required>
                    @error('nama_ruangan') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Lokasi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $ruangan->lokasi) }}" required>
                    @error('lokasi') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kapasitas <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', $ruangan->kapasitas) }}" min="1" required>
                    @error('kapasitas') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Status Ruangan <span style="color:#ef4444;">*</span></label>
                    <select name="status" required>
                        <option value="aktif"     {{ old('status', $ruangan->status) == 'aktif'     ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif"  {{ old('status', $ruangan->status) == 'nonaktif'  ? 'selected' : '' }}>Nonaktif</option>
                        <option value="perawatan" {{ old('status', $ruangan->status) == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Layout Ruangan yang Dapat Diterapkan</label>
                <p class="form-hint">Centang layout yang berlaku untuk ruangan ini.</p>
                <div class="facility-list" style="margin-top:10px;">
                    @foreach($layouts as $layoutItem)
                    <label>
                        <input type="checkbox" name="layouts[]" value="{{ $layoutItem->id }}"
                               {{ $ruangan->layouts->contains($layoutItem->id) ? 'checked' : '' }}>
                        {{ $layoutItem->nama_layout }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-action">
                <a href="{{ route('admin.ruangan.index') }}" class="btn-secondary">
                    <i class="bi bi-x"></i> Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

</x-app-layout>