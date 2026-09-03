<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-calendar-plus" style="color:#005baa;margin-right:8px;"></i>Buat Pemesanan Ruangan</h1>
        <p>Ajukan penggunaan fasilitas kantor untuk kegiatan / rapat Anda.</p>
    </div>
    <a href="<?php echo e(route('pemesanan.index')); ?>" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-file-earmark-text"></i> Formulir Pengajuan Pemesanan</h2>
    </div>

    <div style="padding:28px 32px;">


        <form method="POST" action="<?php echo e(route('pemesanan.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <div class="form-row">
                <div class="form-group">
                    <label class="required">Ruangan</label>
                    <select id="ruangan_id" name="ruangan_id" required>
                        <option value="">-- Pilih Ruangan --</option>
                        <?php $__currentLoopData = $ruangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>" data-kapasitas="<?php echo e($item->kapasitas); ?>" <?php echo e(old('ruangan_id') == $item->id ? 'selected' : ''); ?>>
                                <?php echo e($item->nama_ruangan); ?> (Kapasitas Maks: <?php echo e($item->kapasitas); ?> Orang)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['ruangan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group" id="layout-select-group">
                    <label>Layout Ruangan</label>
                    <select id="layout_ruangan_id" name="layout_ruangan_id">
                        <option value="">-- Pilih Ruangan Dahulu --</option>
                    </select>
                    <div id="single-layout-info" style="display:none;padding:10px 14px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;color:#047857;font-weight:600;font-size:13px;margin-top:6px;">
                    </div>
                    <?php $__errorArgs = ['layout_ruangan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;" class="form-row-3">
                <div class="form-group">
                    <label class="required">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" value="<?php echo e(old('tanggal_kegiatan')); ?>" min="<?php echo e(now('Asia/Makassar')->toDateString()); ?>" required>
                    <?php $__errorArgs = ['tanggal_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="required">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" value="<?php echo e(old('waktu_mulai')); ?>" required>
                    <?php $__errorArgs = ['waktu_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="required">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" value="<?php echo e(old('waktu_selesai')); ?>" required>
                    <?php $__errorArgs = ['waktu_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div id="availability-status" style="display:none;margin-bottom:20px;padding:12px 16px;border-radius:10px;font-weight:600;font-size:13.5px;transition:all 0.3s ease;">
            </div>

            
            <div class="form-row">
                <div class="form-group">
                    <label class="required">Judul Kegiatan</label>
                    <input type="text" name="judul_kegiatan" value="<?php echo e(old('judul_kegiatan')); ?>" maxlength="150" placeholder="Masukkan judul kegiatan / rapat (Maks. 150 karakter)" required>
                    <?php $__errorArgs = ['judul_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="required">Jumlah Tamu</label>
                    <input type="number" name="jumlah_tamu" value="<?php echo e(old('jumlah_tamu')); ?>" min="1" placeholder="Jumlah peserta / tamu" required>
                    <div id="capacity-status" style="display:none;margin-top:8px;padding:10px 14px;border-radius:10px;font-weight:600;font-size:13px;transition:all 0.3s ease;">
                    </div>
                    <?php $__errorArgs = ['jumlah_tamu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;" class="form-row-3">
                <div class="form-group">
                    <label class="required">PIC Kegiatan</label>
                    <input type="text" name="pic_kegiatan" value="<?php echo e(old('pic_kegiatan', auth()->user()->name)); ?>" placeholder="Nama penanggung jawab kegiatan" required>
                    <?php $__errorArgs = ['pic_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="required">Jenis PIC</label>
                    <select name="jenis_pic" required>
                        <option value="Organik" <?php echo e(old('jenis_pic', 'Organik') == 'Organik' ? 'selected' : ''); ?>>Organik</option>
                        <option value="Non Organik" <?php echo e(old('jenis_pic') == 'Non Organik' ? 'selected' : ''); ?>>Non Organik</option>
                    </select>
                    <?php $__errorArgs = ['jenis_pic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label><i class="bi bi-whatsapp" style="color:#25d366;margin-right:4px;"></i> No. WhatsApp Notifikasi</label>
                    <input type="text" name="no_wa_pic" value="<?php echo e(old('no_wa_pic', auth()->user()->no_wa)); ?>" placeholder="Contoh: 081234567890">
                    <span class="form-hint">Terima info persetujuan via WhatsApp</span>
                    <?php $__errorArgs = ['no_wa_pic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="form-row">
                <div class="form-group">
                    <label>Keterangan Layout</label>
                    <textarea name="keterangan_layout" rows="3" placeholder="Catatan khusus tata letak meja / kursi (opsional)..."><?php echo e(old('keterangan_layout')); ?></textarea>
                    <?php $__errorArgs = ['keterangan_layout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label>Catatan Tambahan</label>
                    <textarea name="catatan_user" rows="3" placeholder="Catatan tambahan untuk petugas (opsional)..."><?php echo e(old('catatan_user')); ?></textarea>
                    <?php $__errorArgs = ['catatan_user'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="form-group">
                <label>Upload Lembar Disposisi <span style="color:#dc2626;font-weight:700;">*</span> <small style="color:#64748b;font-weight:400;">(Wajib — PDF / PNG / JPG, Max 5MB)</small></label>
                <div style="border:2px dashed #cbd5e1;border-radius:10px;padding:20px;text-align:center;background:#f8fafc;cursor:pointer;transition:border-color .2s;" id="dropzone-disposisi" onclick="document.getElementById('file_disposisi_input').click()">
                    <div id="dropzone-placeholder">
                        <i class="bi bi-cloud-upload" style="font-size:26px;color:#005baa;display:block;margin-bottom:6px;"></i>
                        <p style="margin:0;font-size:13px;color:#64748b;">Klik atau seret file ke sini untuk mengunggah</p>
                        <p style="margin:4px 0 0 0;font-size:11px;color:#94a3b8;">PDF, JPG, JPEG, PNG — Maksimal 5MB</p>
                    </div>
                    <div id="dropzone-preview" style="display:none;">
                        <i class="bi bi-file-earmark-check" style="font-size:26px;color:#16a34a;display:block;margin-bottom:4px;"></i>
                        <p id="dropzone-filename" style="margin:0;font-size:13px;color:#16a34a;font-weight:600;"></p>
                        <p style="margin:4px 0 0 0;font-size:11px;color:#94a3b8;">Klik untuk mengganti file</p>
                    </div>
                </div>
                <input type="file" id="file_disposisi_input" name="file_disposisi" accept=".pdf,.png,.jpg,.jpeg" required style="display:none;">
                <?php $__errorArgs = ['file_disposisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <script>
            document.getElementById('file_disposisi_input').addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    document.getElementById('dropzone-placeholder').style.display = 'none';
                    document.getElementById('dropzone-preview').style.display = 'block';
                    document.getElementById('dropzone-filename').textContent = file.name;
                    document.getElementById('dropzone-disposisi').style.borderColor = '#16a34a';
                    document.getElementById('dropzone-disposisi').style.background = '#f0fdf4';
                }
            });
            // Drag & Drop support
            const dz = document.getElementById('dropzone-disposisi');
            dz.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor = '#005baa'; });
            dz.addEventListener('dragleave', function() { this.style.borderColor = '#cbd5e1'; });
            dz.addEventListener('drop', function(e) {
                e.preventDefault();
                const input = document.getElementById('file_disposisi_input');
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            });
            </script>

            
            <div class="form-action" style="margin-top:16px;border-top:1px solid #f1f5f9;padding-top:20px;">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-secondary">
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
const oldLayoutId = "<?php echo e(old('layout_ruangan_id')); ?>";

function loadLayouts(ruanganId) {
    const layoutGroup = document.getElementById('layout-select-group');
    const layout = document.getElementById('layout_ruangan_id');
    const singleLayoutInfo = document.getElementById('single-layout-info');

    if (layout) layout.style.display = 'block';
    if (singleLayoutInfo) singleLayoutInfo.style.display = 'none';

    if (!ruanganId) {
        if (layout) {
            layout.style.display = 'block';
            layout.innerHTML = '<option value="">-- Pilih Ruangan Dahulu --</option>';
            layout.value = '';
        }
        if (singleLayoutInfo) singleLayoutInfo.style.display = 'none';
        return;
    }

    if (layout) layout.innerHTML = '<option value="">Memuat layout...</option>';

    fetch(`/api/ruangan/${ruanganId}/layouts`)
        .then(response => response.json())
        .then(result => {
            if (result.length === 0) {
                // Tampilkan keterangan bahwa tidak ada layout khusus (tanpa memuat 6 layout umum)
                if (layout) {
                    layout.style.display = 'block';
                    layout.innerHTML = '<option value="">-- Tidak ada layout khusus untuk ruangan ini --</option>';
                    layout.value = '';
                }
                if (singleLayoutInfo) singleLayoutInfo.style.display = 'none';
            } else if (result.length === 1) {
                if (layout) {
                    layout.innerHTML = `<option value="${result[0].id}" selected>${result[0].nama_layout}</option>`;
                    layout.value = result[0].id;
                    layout.style.display = 'none';
                }

                if (singleLayoutInfo) {
                    singleLayoutInfo.style.display = 'block';
                    singleLayoutInfo.innerHTML = `<i class="bi bi-info-circle-fill" style="margin-right: 6px;"></i> Layout Otomatis: <strong>${result[0].nama_layout}</strong>`;
                }
            } else {
                if (layout) {
                    layout.style.display = 'block';
                    layout.innerHTML = '<option value="">-- Pilih Layout --</option>';

                    result.forEach(item => {
                        const isSelected = oldLayoutId && oldLayoutId == item.id ? 'selected' : '';
                        layout.innerHTML += `<option value="${item.id}" ${isSelected}>${item.nama_layout}</option>`;
                    });
                }
                if (singleLayoutInfo) singleLayoutInfo.style.display = 'none';
            }

            if (typeof checkCapacity === 'function') {
                checkCapacity();
            }
        })
        .catch(() => {
            if (layout) {
                layout.style.display = 'block';
                layout.innerHTML = '<option value="">Gagal memuat layout</option>';
            }
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

    const todayStr = '<?php echo e(now("Asia/Makassar")->toDateString()); ?>';
    const nowHourMin = '<?php echo e(now("Asia/Makassar")->format("H:i")); ?>';

    // Update dynamic min attribute pada input waktu
    if (tanggal === todayStr) {
        waktuMulaiInput.min = nowHourMin;
    } else {
        waktuMulaiInput.removeAttribute('min');
    }
    if (waktuMulai) {
        waktuSelesaiInput.min = waktuMulai;
    }

    if (tanggal && tanggal < todayStr) {
        if (availabilityBox) {
            availabilityBox.style.display = 'block';
            availabilityBox.style.background = '#fff1f2';
            availabilityBox.style.color = '#be123c';
            availabilityBox.style.border = '1px solid #fecdd3';
            availabilityBox.innerHTML = '<i class="bi bi-x-circle-fill" style="margin-right: 8px;"></i> Tanggal kegiatan sudah lewat! Silakan pilih hari ini atau tanggal mendatang.';
        }
        isScheduleValid = false;
        updateSubmitButtonState();
        return;
    }

    if (tanggal === todayStr && waktuMulai && waktuMulai < nowHourMin) {
        if (availabilityBox) {
            availabilityBox.style.display = 'block';
            availabilityBox.style.background = '#fff1f2';
            availabilityBox.style.color = '#be123c';
            availabilityBox.style.border = '1px solid #fecdd3';
            availabilityBox.innerHTML = `<i class="bi bi-x-circle-fill" style="margin-right: 8px;"></i> Waktu mulai (${waktuMulai} WITA) sudah terlewat! Waktu saat ini adalah ${nowHourMin} WITA.`;
        }
        isScheduleValid = false;
        updateSubmitButtonState();
        return;
    }

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

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/pemesanan/create.blade.php ENDPATH**/ ?>