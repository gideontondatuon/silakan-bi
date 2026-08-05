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

    <h1>
        Detail Pemesanan
    </h1>

    <p>
        Review informasi pengajuan penggunaan ruangan sebelum persetujuan.
    </p>

</div>


<div class="detail-container">


<div class="detail-card">

    <div class="detail-title">

        <i class="bi bi-person-circle"></i>

        Informasi Pemohon

    </div>


    <div class="detail-grid">


        <div>
            <label>
                Nama Pemohon
            </label>

            <p>
                <?php echo e($pemesanan->user->name); ?>

            </p>
        </div>


        <div>
            <label>
                Kode Pemesanan
            </label>

            <p>
                <?php echo e($pemesanan->kode_pemesanan); ?>

            </p>
        </div>


    </div>


</div>



<div class="detail-card">


    <div class="detail-title">

        <i class="bi bi-calendar-event"></i>

        Informasi Kegiatan

    </div>



    <div class="detail-grid">


        <div>

            <label>
                Judul Kegiatan
            </label>

            <p>
                <?php echo e($pemesanan->judul_kegiatan); ?>

            </p>

        </div>



        <div>

            <label>
                PIC Kegiatan
            </label>

            <p>
                <?php echo e($pemesanan->pic_kegiatan); ?>

            </p>

        </div>



        <div>

            <label>
                Jenis PIC
            </label>

            <p>
                <?php echo e($pemesanan->jenis_pic); ?>

            </p>

        </div>



        <div>

            <label>
                Jumlah Tamu
            </label>

            <p>
                <?php echo e($pemesanan->jumlah_tamu); ?> orang
            </p>

        </div>



        <div>

            <label>
                Tanggal
            </label>

            <p>
                <?php echo e($pemesanan->tanggal_kegiatan->format('d-m-Y')); ?>

            </p>

        </div>



        <div>

            <label>
                Waktu
            </label>

            <p>
                <?php echo e($pemesanan->waktu_mulai); ?>

                -
                <?php echo e($pemesanan->waktu_selesai); ?>

            </p>

        </div>


    </div>


</div>



<div class="detail-card">


    <div class="detail-title">

        <i class="bi bi-building"></i>

        Informasi Ruangan

    </div>



    <div class="detail-grid">


        <div>

            <label>
                Ruangan
            </label>

            <p>
                <?php echo e($pemesanan->ruangan->nama_ruangan); ?>

            </p>

        </div>



        <div>

            <label>
                Layout
            </label>

            <p>
                <?php echo e($pemesanan->layout->nama_layout); ?>

            </p>

        </div>


    </div>



    <label>
        Fasilitas
    </label>


    <div class="facility-list">

    <?php $__currentLoopData = $pemesanan->ruangan->fasilitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fasilitas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <span>
            <i class="bi bi-check-circle"></i>
            <?php echo e($fasilitas->nama_fasilitas); ?>

        </span>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>


    <?php if($pemesanan->keterangan_layout): ?>

    <div class="layout-note">

        <label>
            Keterangan Layout
        </label>

        <p>
            <?php echo e($pemesanan->keterangan_layout); ?>

        </p>

    </div>

    <?php endif; ?>


</div>




<div class="approval-action">


<a href="<?php echo e(route('admin.approval.index')); ?>"
class="btn-secondary">

<i class="bi bi-arrow-left"></i>

Kembali

</a>



<form method="POST"
action="<?php echo e(route('admin.approval.reject',$pemesanan)); ?>">

<?php echo csrf_field(); ?>


<input type="text"
name="alasan_penolakan"
class="reject-input"
placeholder="Alasan penolakan"
required>


<button class="btn-danger">

<i class="bi bi-x-circle"></i>

Tolak

</button>


</form>




<form method="POST"
action="<?php echo e(route('admin.approval.approve',$pemesanan)); ?>">

<?php echo csrf_field(); ?>


<button class="btn-primary">

<i class="bi bi-check-circle"></i>

Setujui

</button>


</form>


</div>


</div>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/approval/show.blade.php ENDPATH**/ ?>