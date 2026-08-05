<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-calendar-plus" style="color:#005baa;margin-right:8px;"></i>Buat Pemesanan Ruangan</h1>
        <p>Ajukan penggunaan fasilitas kantor untuk kegiatan / rapat Anda.</p>
    </div>
    <a href="{{ route('pemesanan.index') }}" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-file-earmark-text"></i> Formulir Pengajuan Pemesanan</h2>
    </div>

    <div style="padding:28px 32px;">

        {{-- Flash Notification Alerts --}}
        @if(session('success'))
        <div class="alert alert-success" style="padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;display:flex;align-items:center;gap:10px;background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;">
            <i class="bi bi-check-circle-fill" style="font-size:1.2rem;"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger" style="padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;display:flex;align-items:center;gap:10px;background:#fff1f2;border:1px solid #fecdd3;color:#be123c;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:1.2rem;"></i>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger" style="padding:16px 20px;border-radius:10px;margin-bottom:24px;background:#fff1f2;border:1px solid #fecdd3;color:#be123c;">
            <div style="font-weight:700;display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                <i class="bi bi-x-circle-fill" style="font-size:1.2rem;"></i>
                <span>Pengajuan pemesanan gagal dikirim. Silakan periksa kesalahan berikut:</span>
            </div>
            <ul style="margin:0;padding-left:24px;font-size:13.5px;font-weight:500;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('pemesanan.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Row 1: Ruangan & Layout --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="required">Ruangan</label>
                    <select id="ruangan_id" name="ruangan_id" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($ruangan as $item)
                            <option value="{{ $item->id }}" data-kapasitas="{{ $item->kapasitas }}" {{ old('ruangan_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_ruangan }} (Kapasitas Maks: {{ $item->kapasitas }} Orang)
                            </option>
                        @endforeach
                    </select>
                    @error('ruangan_id')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" id="layout-select-group">
                    <label>Layout Ruangan</label>
                    <select id="layout_ruangan_id" name="layout_ruangan_id">
                        <option value="">-- Pilih Ruangan Dahulu --</option>
                    </select>
                    <div id="single-layout-info" style="display:none;padding:10px 14px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;color:#047857;font-weight:600;font-size:13px;margin-top:6px;">
                    </div>
                    @error('layout_ruangan_id')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Row 2: Tanggal, Waktu Mulai, Waktu Selesai --}}
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;" class="form-row-3">
                <div class="form-group">
                    <label class="required">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}" required>
                    @error('tanggal_kegiatan')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="required">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
                    @error('waktu_mulai')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="required">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
                    @error('waktu_selesai')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div id="availability-status" style="display:none;margin-bottom:20px;padding:12px 16px;border-radius:10px;font-weight:600;font-size:13.5px;transition:all 0.3s ease;">
            </div>

            {{-- Row 3: Judul Kegiatan & Jumlah Tamu --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="required">Judul Kegiatan</label>
                    <input type="text" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}" maxlength="150" placeholder="Masukkan judul kegiatan / rapat (Maks. 150 karakter)" required>
                    @error('judul_kegiatan')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="required">Jumlah Tamu</label>
                    <input type="number" name="jumlah_tamu" value="{{ old('jumlah_tamu') }}" min="1" placeholder="Jumlah peserta / tamu" required>
                    <div id="capacity-status" style="display:none;margin-top:8px;padding:10px 14px;border-radius:10px;font-weight:600;font-size:13px;transition:all 0.3s ease;">
                    </div>
                    @error('jumlah_tamu')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Row 4: PIC Kegiatan, Jenis PIC, & No WA --}}
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;" class="form-row-3">
                <div class="form-group">
                    <label class="required">PIC Kegiatan</label>
                    <input type="text" name="pic_kegiatan" value="{{ old('pic_kegiatan', auth()->user()->name) }}" placeholder="Nama penanggung jawab kegiatan" required>
                    @error('pic_kegiatan')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="required">Jenis PIC</label>
                    <select name="jenis_pic" required>
                        <option value="Organik" {{ old('jenis_pic', 'Organik') == 'Organik' ? 'selected' : '' }}>Organik</option>
                        <option value="Non Organik" {{ old('jenis_pic') == 'Non Organik' ? 'selected' : '' }}>Non Organik</option>
                    </select>
                    @error('jenis_pic')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="bi bi-whatsapp" style="color:#25d366;margin-right:4px;"></i> No. WhatsApp Notifikasi</label>
                    <input type="text" name="no_wa_pic" value="{{ old('no_wa_pic', auth()->user()->no_wa) }}" placeholder="Contoh: 081234567890">
                    <span class="form-hint">Terima info persetujuan via WhatsApp</span>
                    @error('no_wa_pic')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Row 5: Keterangan Layout & Catatan --}}
            <div class="form-row">
                <div class="form-group">
                    <label>Keterangan Layout</label>
                    <textarea name="keterangan_layout" rows="3" placeholder="Catatan khusus tata letak meja / kursi (opsional)...">{{ old('keterangan_layout') }}</textarea>
                    @error('keterangan_layout')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Catatan Tambahan</label>
                    <textarea name="catatan_user" rows="3" placeholder="Catatan tambahan untuk petugas (opsional)...">{{ old('catatan_user') }}</textarea>
                    @error('catatan_user')
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Row 6: Upload Lembar Disposisi --}}
            <div class="form-group">
                <label>Upload Lembar Disposisi <small style="color:#64748b;font-weight:400;">(PDF / PNG / JPG, Max 5MB)</small></label>
                <input type="file" name="file_disposisi" accept=".pdf,.png,.jpg,.jpeg">
                @error('file_disposisi')
                    <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="form-action" style="margin-top:16px;border-top:1px solid #f1f5f9;padding-top:20px;">
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    <i class="bi bi-x"></i> Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-send"></i> Kirim Pengajuan Pemesanan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
const oldLayoutId = "{{ old('layout_ruangan_id') }}";

function loadLayouts(ruanganId) {
    const layout = document.getElementById('layout_ruangan_id');
    const singleLayoutInfo = document.getElementById('single-layout-info');

    layout.style.display = 'block';
    if (singleLayoutInfo) singleLayoutInfo.style.display = 'none';

    if (!ruanganId) {
        layout.innerHTML = '<option value="">-- Pilih Ruangan Dahulu --</option>';
        return;
    }

    layout.innerHTML = '<option value="">Memuat layout...</option>';

    fetch(`/api/ruangan/${ruanganId}/layouts`)
        .then(response => response.json())
        .then(result => {
            if (result.length === 0) {
                layout.style.display = 'block';
                if (singleLayoutInfo) singleLayoutInfo.style.display = 'none';
                layout.innerHTML = '<option value="">-- Tidak ada layout khusus --</option>';
            } else if (result.length === 1) {
                layout.innerHTML = `<option value="${result[0].id}" selected>${result[0].nama_layout}</option>`;
                layout.style.display = 'none';

                if (singleLayoutInfo) {
                    singleLayoutInfo.style.display = 'block';
                    singleLayoutInfo.innerHTML = `<i class="bi bi-info-circle-fill" style="margin-right: 6px;"></i> Layout Otomatis: <strong>${result[0].nama_layout}</strong>`;
                }
            } else {
                layout.style.display = 'block';
                if (singleLayoutInfo) singleLayoutInfo.style.display = 'none';
                layout.innerHTML = '<option value="">-- Pilih Layout --</option>';

                result.forEach(item => {
                    const isSelected = oldLayoutId && oldLayoutId == item.id ? 'selected' : '';
                    layout.innerHTML += `<option value="${item.id}" ${isSelected}>${item.nama_layout}</option>`;
                });
            }

            if (typeof checkCapacity === 'function') {
                checkCapacity();
            }
        })
        .catch(() => {
            layout.style.display = 'block';
            if (singleLayoutInfo) singleLayoutInfo.style.display = 'none';
            layout.innerHTML = '<option value="">Gagal memuat layout</option>';
        });
}

document.getElementById('ruangan_id').addEventListener('change', function () {
    loadLayouts(this.value);
});

let isScheduleValid = true;
let isCapacityValid = true;

const ruanganSelect = document.getElementById('ruangan_id');
const layoutSelect = document.getElementById('layout_ruangan_id');
const jumlahTamuInput = document.querySelector('input[name="jumlah_tamu"]');
const tanggalInput = document.querySelector('input[name="tanggal_kegiatan"]');
const waktuMulaiInput = document.querySelector('input[name="waktu_mulai"]');
const waktuSelesaiInput = document.querySelector('input[name="waktu_selesai"]');

const availabilityBox = document.getElementById('availability-status');
const capacityBox = document.getElementById('capacity-status');
const submitBtn = document.querySelector('.form-action .btn-primary');

function updateSubmitButtonState() {
    if (submitBtn) {
        if (isScheduleValid && isCapacityValid) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
        }
    }
}

function checkCapacity() {
    if (!ruanganSelect || !jumlahTamuInput) return;

    const selectedOption = ruanganSelect.options[ruanganSelect.selectedIndex];
    const kapasitas = selectedOption ? parseInt(selectedOption.getAttribute('data-kapasitas') || 0) : 0;
    const jumlahTamu = parseInt(jumlahTamuInput.value || 0);

    if (!ruanganSelect.value || !jumlahTamuInput.value) {
        if (capacityBox) capacityBox.style.display = 'none';
        isCapacityValid = true;
        updateSubmitButtonState();
        return;
    }

    if (kapasitas > 0 && jumlahTamu > kapasitas) {
        if (capacityBox) {
            capacityBox.style.display = 'block';
            capacityBox.style.background = '#fff1f2';
            capacityBox.style.color = '#be123c';
            capacityBox.style.border = '1px solid #fecdd3';
            capacityBox.innerHTML = `<i class="bi bi-exclamation-triangle-fill" style="margin-right: 8px;"></i> Jumlah tamu (${jumlahTamu} orang) melebihi kapasitas maksimal ruangan yang dipilih (${kapasitas} orang).`;
        }
        isCapacityValid = false;
    } else if (kapasitas > 0 && jumlahTamu <= kapasitas) {
        if (capacityBox) {
            capacityBox.style.display = 'block';
            capacityBox.style.background = '#ecfdf5';
            capacityBox.style.color = '#047857';
            capacityBox.style.border = '1px solid #a7f3d0';
            capacityBox.innerHTML = `<i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i> Jumlah tamu sesuai dengan kapasitas maksimal ruangan (${kapasitas} orang).`;
        }
        isCapacityValid = true;
    } else {
        if (capacityBox) capacityBox.style.display = 'none';
        isCapacityValid = true;
    }

    updateSubmitButtonState();
}

function checkScheduleAvailability() {
    const ruanganId = ruanganSelect ? ruanganSelect.value : '';
    const tanggal = tanggalInput ? tanggalInput.value : '';
    const waktuMulai = waktuMulaiInput ? waktuMulaiInput.value : '';
    const waktuSelesai = waktuSelesaiInput ? waktuSelesaiInput.value : '';

    if (!ruanganId || !tanggal || !waktuMulai || !waktuSelesai) {
        if (availabilityBox) availabilityBox.style.display = 'none';
        isScheduleValid = true;
        updateSubmitButtonState();
        return;
    }

    if (waktuMulai >= waktuSelesai) {
        if (availabilityBox) {
            availabilityBox.style.display = 'block';
            availabilityBox.style.background = '#fff1f2';
            availabilityBox.style.color = '#be123c';
            availabilityBox.style.border = '1px solid #fecdd3';
            availabilityBox.innerHTML = '<i class="bi bi-exclamation-triangle-fill" style="margin-right: 8px;"></i> Waktu selesai harus lebih akhir dari waktu mulai.';
        }
        isScheduleValid = false;
        updateSubmitButtonState();
        return;
    }

    if (availabilityBox) {
        availabilityBox.style.display = 'block';
        availabilityBox.style.background = '#f8fafc';
        availabilityBox.style.color = '#475569';
        availabilityBox.style.border = '1px solid #cbd5e1';
        availabilityBox.innerHTML = '<i class="bi bi-arrow-repeat" style="margin-right: 8px; display: inline-block;"></i> Memeriksa ketersediaan jadwal ruangan...';
    }

    const params = new URLSearchParams({
        ruangan_id: ruanganId,
        tanggal_kegiatan: tanggal,
        waktu_mulai: waktuMulai,
        waktu_selesai: waktuSelesai
    });

    fetch(`/api/pemesanan/check-conflict?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            if (data.conflict) {
                if (availabilityBox) {
                    availabilityBox.style.background = '#fff1f2';
                    availabilityBox.style.color = '#be123c';
                    availabilityBox.style.border = '1px solid #fecdd3';
                    availabilityBox.innerHTML = `<i class="bi bi-x-circle-fill" style="margin-right: 8px;"></i> ${data.message}`;
                }
                isScheduleValid = false;
            } else {
                if (availabilityBox) {
                    availabilityBox.style.background = '#ecfdf5';
                    availabilityBox.style.color = '#047857';
                    availabilityBox.style.border = '1px solid #a7f3d0';
                    availabilityBox.innerHTML = `<i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i> ${data.message}`;
                }
                isScheduleValid = true;
            }
            updateSubmitButtonState();
        })
        .catch(() => {
            if (availabilityBox) availabilityBox.style.display = 'none';
            isScheduleValid = true;
            updateSubmitButtonState();
        });
}

document.addEventListener('DOMContentLoaded', function() {
    if (ruanganSelect && ruanganSelect.value) {
        loadLayouts(ruanganSelect.value);
    }
    checkScheduleAvailability();
    checkCapacity();
});

[ruanganSelect, tanggalInput, waktuMulaiInput, waktuSelesaiInput].forEach(el => {
    if (el) {
        el.addEventListener('change', checkScheduleAvailability);
        el.addEventListener('input', checkScheduleAvailability);
    }
});

[layoutSelect, jumlahTamuInput].forEach(el => {
    if (el) {
        el.addEventListener('change', checkCapacity);
        el.addEventListener('input', checkCapacity);
    }
});
</script>

<style>
@media (max-width: 768px) {
    .form-row-3 {
        grid-template-columns: 1fr !important;
    }
}
</style>

</x-app-layout>