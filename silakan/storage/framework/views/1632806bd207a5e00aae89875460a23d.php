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
        Informasi pengajuan penggunaan ruangan.
    </p>

</div>


<div class="dashboard-section">


<div class="detail-grid">


<div>

<label>
Kode Pemesanan
</label>

<p>
<?php echo e($pemesanan->kode_pemesanan); ?>

</p>

</div>



<div>

<label>
Status
</label>

<p>

<?php if($pemesanan->status->value == 'Pending'): ?>

<span class="badge badge-warning">

<i class="bi bi-clock"></i>

Pending

</span>


<?php elseif($pemesanan->status->value == 'Disetujui'): ?>

<span class="badge badge-success">

<i class="bi bi-check-circle"></i>

Disetujui

</span>


<?php else: ?>

<span class="badge badge-danger">

<i class="bi bi-x-circle"></i>

Ditolak

</span>


<?php endif; ?>

</p>

</div>



<div>

<label>
Kegiatan
</label>

<p>
<?php echo e($pemesanan->judul_kegiatan); ?>

</p>

</div>



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


<?php if($pemesanan->catatan_user): ?>


<hr>


<h3>
Catatan
</h3>


<p>
<?php echo e($pemesanan->catatan_user); ?>

</p>


<?php endif; ?>



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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/pemesanan/show.blade.php ENDPATH**/ ?>