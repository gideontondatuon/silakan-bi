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
        <h1><i class="bi bi-calendar-plus-fill" style="color:#005baa;margin-right:8px;"></i>Tambah Rapat (Admin)</h1>
        <p>Jadwalkan rapat atau kegiatan secara langsung dari sisi Administrator. Rapat langsung berstatus <strong>Disetujui</strong>.</p>
    </div>
    <a href="<?php echo e(route('admin.approval.index')); ?>" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section">
    <div class="section-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <h2><i class="bi bi-calendar-event"></i> Formulir Penjadwalan Rapat</h2>
        <span class="badge badge-success" style="padding:6px 14px;border-radius:9999px;font-size:12px;font-weight:700;">
            <i class="bi bi-check-circle-fill"></i> Otomatis Disetujui
        </span>
    </div>

    <div style="padding:28px 32px;">

        <div style="padding:14px 18px;border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;font-size:13px;line-height:1.5;margin-bottom:24px;display:flex;align-items:flex-start;gap:12px;">
            <i class="bi bi-lightning-charge-fill" style="font-size:20px;color:#16a34a;flex-shrink:0;"></i>
            <div>
                <strong>Penjadwalan Instan Administrator:</strong>
                <p style="margin:2px 0 0 0;color:#15803d;font-size:12.5px;">Rapat yang dibuat melalui formulir ini tidak memerlukan verifikasi persetujuan lagi. Status pemesanan langsung <strong>Disetujui</strong> dan seketika tercatat pada kalender ruangan serta layar monitor TV Lobby.</p>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('admin.approval.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <div class="form-row">
                <div class="form-group">
                    <label class="required">Pilih Ruangan</label>
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
                    <label>Tata Letak / Layout Ruangan</label>
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

            <?php
                $nowMakassar = now('Asia/Makassar');
                $defaultStart = $nowMakassar->copy()->addMinutes(30)->format('H:i');
                $defaultEnd = $nowMakassar->copy()->addMinutes(150)->format('H:i');
            ?>

            
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;" class="form-row-3">
                <div class="form-group">
                    <label class="required">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" value="<?php echo e(old('tanggal_kegiatan', $nowMakassar->toDateString())); ?>" min="<?php echo e($nowMakassar->toDateString()); ?>" required>
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
                    <input type="time" name="waktu_mulai" id="waktu_mulai" value="<?php echo e(old('waktu_mulai', $defaultStart)); ?>" required>
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
                    <input type="time" name="waktu_selesai" id="waktu_selesai" value="<?php echo e(old('waktu_selesai', $defaultEnd)); ?>" required>
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
                    <label class="required">Judul Kegiatan / Rapat</label>
                    <input type="text" name="judul_kegiatan" value="<?php echo e(old('judul_kegiatan')); ?>" maxlength="150" placeholder="Contoh: Rapat Koordinasi Pimpinan Daerah (Maks. 150 karakter)" required>
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
                    <label class="required">Jumlah Peserta / Tamu</label>
                    <input type="number" id="jumlah_tamu" name="jumlah_tamu" value="<?php echo e(old('jumlah_tamu', 10)); ?>" min="1" placeholder="Jumlah peserta" required>
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

            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="form-row">
                <div class="form-group">
                    <label>Penyelenggara / Akun Pemesan</label>
                    <select name="user_id">
                        <option value="<?php echo e(auth()->id()); ?>" <?php echo e(old('user_id') == auth()->id() ? 'selected' : ''); ?>>
                            Administrator
                        </option>
                        <optgroup label="Unit Kerja Terdaftar">
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unit->id); ?>" <?php echo e(old('user_id') == $unit->id ? 'selected' : ''); ?>>
                                    <?php echo e($unit->nama_unit ?? $unit->name); ?> (<?php echo e($unit->username); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </optgroup>
                    </select>
                    <span class="form-hint">Pilih unit kerja penyelenggara atau biarkan sebagai Administrator.</span>
                    <?php $__errorArgs = ['user_id'];
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
                    <label class="required">Nama PIC Kegiatan</label>
                    <input type="text" name="pic_kegiatan" value="<?php echo e(old('pic_kegiatan', auth()->user()->name ?? 'Administrator Sarpras')); ?>" placeholder="Nama penanggung jawab rapat" required>
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
            </div>

            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="form-row">
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
                    <label><i class="bi bi-whatsapp" style="color:#25d366;margin-right:4px;"></i> No. WhatsApp Notifikasi PIC <small style="color:#64748b;">(Opsional)</small></label>
                    <input type="text" name="no_wa_pic" value="<?php echo e(old('no_wa_pic', auth()->user()->no_wa)); ?>" placeholder="Contoh: 081234567890">
                    <span class="form-hint">PIC akan menerima notifikasi pengingat via WhatsApp</span>
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
                    <label>Keterangan Tata Letak (Layout)</label>
                    <textarea name="keterangan_layout" rows="2" placeholder="Catatan khusus tata letak meja / kursi / proyektor (opsional)..."><?php echo e(old('keterangan_layout')); ?></textarea>
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
                    <textarea name="catatan_user" rows="2" placeholder="Catatan tambahan untuk petugas ruangan (opsional)..."><?php echo e(old('catatan_user')); ?></textarea>
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
                <label>Upload Lembar Disposisi / Nota Dinas <small style="color:#64748b;font-weight:400;">(Opsional untuk Administrator — PDF / JPG / PNG, Max 5MB)</small></label>
                <div style="border:2px dashed #cbd5e1;border-radius:10px;padding:20px;text-align:center;background:#f8fafc;cursor:pointer;transition:border-color .2s;" id="dropzone-disposisi" onclick="document.getElementById('file_disposisi_input').click()">
                    <div id="dropzone-placeholder">
                        <i class="bi bi-cloud-upload" style="font-size:26px;color:#005baa;display:block;margin-bottom:6px;"></i>
                        <p style="margin:0;font-size:13px;color:#64748b;">Klik atau seret file ke sini untuk mengunggah (Opsional)</p>
                        <p style="margin:4px 0 0 0;font-size:11px;color:#94a3b8;">PDF, JPG, JPEG, PNG — Maksimal 5MB</p>
                    </div>
                    <div id="dropzone-preview" style="display:none;">
                        <i class="bi bi-file-earmark-check" style="font-size:26px;color:#16a34a;display:block;margin-bottom:4px;"></i>
                        <p id="dropzone-filename" style="margin:0;font-size:13px;color:#16a34a;font-weight:600;"></p>
                        <p style="margin:4px 0 0 0;font-size:11px;color:#94a3b8;">Klik untuk mengganti file</p>
                    </div>
                </div>
                <input type="file" id="file_disposisi_input" name="file_disposisi" accept=".pdf,.png,.jpg,.jpeg" style="display:none;">
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

            
            <div class="form-action" style="margin-top:20px;border-top:1px solid #f1f5f9;padding-top:20px;display:flex;align-items:center;justify-content:flex-end;gap:12px;">
                <a href="<?php echo e(route('admin.approval.index')); ?>" class="btn-secondary" style="padding:10px 20px;border-radius:10px;">
                    <i class="bi bi-x"></i> Batal
                </a>
                <button type="submit" id="submit-btn" class="btn-primary" style="padding:10px 24px;border-radius:10px;font-weight:700;display:inline-flex;align-items:center;gap:8px;">
                    <i class="bi bi-check2-circle"></i> Simpan &amp; Jadwalkan Rapat
                </button>
            </div>
        </form>
    </div>
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

const dz = document.getElementById('dropzone-disposisi');
dz.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor = '#005baa'; });
dz.addEventListener('dragleave', function() { this.style.borderColor = '#cbd5e1'; });
dz.addEventListener('drop', function(e) {
    e.preventDefault();
    const input = document.getElementById('file_disposisi_input');
    input.files = e.dataTransfer.files;
    input.dispatchEvent(new Event('change'));
});

// Dynamic Layout & Conflict Checker
document.addEventListener('DOMContentLoaded', function() {
    const ruanganSelect = document.getElementById('ruangan_id');
    const layoutSelect = document.getElementById('layout_ruangan_id');
    const layoutGroup = document.getElementById('layout-select-group');
    const singleLayoutInfo = document.getElementById('single-layout-info');
    const tanggalInput = document.getElementById('tanggal_kegiatan');
    const mulaiInput = document.getElementById('waktu_mulai');
    const selesaiInput = document.getElementById('waktu_selesai');
    const statusDiv = document.getElementById('availability-status');
    const submitBtn = document.getElementById('submit-btn');
    const jumlahTamuInput = document.getElementById('jumlah_tamu');
    const capacityStatus = document.getElementById('capacity-status');

    function checkCapacity() {
        const selectedOption = ruanganSelect.options[ruanganSelect.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            capacityStatus.style.display = 'none';
            return;
        }

        const maxKap = parseInt(selectedOption.getAttribute('data-kapasitas')) || 0;
        const inputTamu = parseInt(jumlahTamuInput.value) || 0;

        if (inputTamu > 0 && maxKap > 0) {
            if (inputTamu > maxKap) {
                capacityStatus.style.display = 'block';
                capacityStatus.style.background = '#fef2f2';
                capacityStatus.style.color = '#dc2626';
                capacityStatus.style.border = '1px solid #fecaca';
                capacityStatus.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Melebihi kapasitas maksimal ruangan (${maxKap} orang).`;
            } else {
                capacityStatus.style.display = 'none';
            }
        } else {
            capacityStatus.style.display = 'none';
        }
    }

    jumlahTamuInput.addEventListener('input', checkCapacity);

    function checkConflict() {
        const ruanganId = ruanganSelect.value;
        const tanggal = tanggalInput.value;
        const mulai = mulaiInput.value;
        const todayStr = '<?php echo e(now("Asia/Makassar")->toDateString()); ?>';
        const nowHourMin = '<?php echo e(now("Asia/Makassar")->format("H:i")); ?>';

        if (tanggal === todayStr) {
            mulaiInput.min = nowHourMin;
        } else {
            mulaiInput.removeAttribute('min');
        }
        if (mulai) {
            selesaiInput.min = mulai;
        }

        if (tanggal && tanggal < todayStr) {
            statusDiv.style.display = 'block';
            statusDiv.style.background = '#fef2f2';
            statusDiv.style.color = '#dc2626';
            statusDiv.style.border = '1px solid #fecaca';
            statusDiv.innerHTML = '<i class="bi bi-x-circle-fill"></i> Tanggal kegiatan sudah lewat! Silakan pilih hari ini atau tanggal mendatang.';
            submitBtn.disabled = true;
            return;
        }

        if (tanggal === todayStr && mulai && mulai < nowHourMin) {
            statusDiv.style.display = 'block';
            statusDiv.style.background = '#fef2f2';
            statusDiv.style.color = '#dc2626';
            statusDiv.style.border = '1px solid #fecaca';
            statusDiv.innerHTML = `<i class="bi bi-x-circle-fill"></i> Waktu mulai (${mulai} WITA) sudah terlewat! Waktu saat ini adalah ${nowHourMin} WITA.`;
            submitBtn.disabled = true;
            return;
        }

        if (ruanganId && tanggal && mulai && selesai) {
            if (selesai <= mulai) {
                statusDiv.style.display = 'block';
                statusDiv.style.background = '#fef2f2';
                statusDiv.style.color = '#dc2626';
                statusDiv.style.border = '1px solid #fecaca';
                statusDiv.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Waktu selesai harus lebih besar dari waktu mulai.';
                submitBtn.disabled = true;
                return;
            }

            statusDiv.style.display = 'block';
            statusDiv.style.background = '#eff6ff';
            statusDiv.style.color = '#1d4ed8';
            statusDiv.style.border = '1px solid #bfdbfe';
            statusDiv.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Memeriksa ketersediaan ruangan...';

            fetch(`/api/pemesanan/check-conflict?ruangan_id=${ruanganId}&tanggal_kegiatan=${tanggal}&waktu_mulai=${mulai}&waktu_selesai=${selesai}`)
                .then(res => res.json())
                .then(data => {
                    if (data.conflict) {
                        statusDiv.style.background = '#fef2f2';
                        statusDiv.style.color = '#dc2626';
                        statusDiv.style.border = '1px solid #fecaca';
                        statusDiv.innerHTML = `<i class="bi bi-x-circle-fill"></i> ${data.message || 'Ruangan TIDAK TERSEDIA (sudah ada agenda lain pada jam tersebut).'}`;
                        submitBtn.disabled = true;
                    } else {
                        statusDiv.style.background = '#f0fdf4';
                        statusDiv.style.color = '#16a34a';
                        statusDiv.style.border = '1px solid #bbf7d0';
                        statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Ruangan <strong>TERSEDIA</strong> untuk jadwal tersebut.';
                        submitBtn.disabled = false;
                    }
                })
                .catch(() => {
                    statusDiv.style.display = 'none';
                    submitBtn.disabled = false;
                });
        } else {
            statusDiv.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    ruanganSelect.addEventListener('change', function() {
        const ruanganId = this.value;
        layoutSelect.innerHTML = '<option value="">-- Memuat Layout... --</option>';
        singleLayoutInfo.style.display = 'none';
        layoutSelect.style.display = 'block';
        checkCapacity();

        if (ruanganId) {
            fetch(`/api/ruangan/${ruanganId}/layouts`)
                .then(res => res.json())
                .then(layouts => {
                    layoutSelect.innerHTML = '';
                    if (layouts.length === 0) {
                        layoutSelect.innerHTML = '<option value="">Tidak ada pilihan layout khusus (Standar)</option>';
                    } else if (layouts.length === 1) {
                        const l = layouts[0];
                        layoutSelect.innerHTML = `<option value="${l.id}" selected>${l.nama_layout}</option>`;
                        layoutSelect.style.display = 'none';
                        singleLayoutInfo.style.display = 'block';
                        singleLayoutInfo.innerHTML = `<i class="bi bi-check2-circle"></i> Tata Letak: <strong>${l.nama_layout}</strong> (Layout Baku)`;
                    } else {
                        layoutSelect.innerHTML = '<option value="">-- Pilih Tata Letak / Layout --</option>';
                        layouts.forEach(l => {
                            layoutSelect.innerHTML += `<option value="${l.id}">${l.nama_layout}</option>`;
                        });
                    }
                });
        } else {
            layoutSelect.innerHTML = '<option value="">-- Pilih Ruangan Dahulu --</option>';
        }

        checkConflict();
    });

    tanggalInput.addEventListener('change', checkConflict);
    mulaiInput.addEventListener('change', checkConflict);
    selesaiInput.addEventListener('change', checkConflict);

    if (ruanganSelect.value) {
        ruanganSelect.dispatchEvent(new Event('change'));
    }
});
</script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\Bank Indo\silakan\resources\views/admin/approval/create.blade.php ENDPATH**/ ?>