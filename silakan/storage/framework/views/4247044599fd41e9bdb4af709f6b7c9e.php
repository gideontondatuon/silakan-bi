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
        Pemesanan Saya
    </h1>

    <p>
        Daftar pengajuan penggunaan ruangan.
    </p>

</div>


<?php if(session('success')): ?>

<div class="alert-success">

    <i class="bi bi-check-circle"></i>

    <?php echo e(session('success')); ?>


</div>

<?php endif; ?>


<?php if(session('error')): ?>

<div class="alert-error">

    <i class="bi bi-exclamation-circle"></i>

    <?php echo e(session('error')); ?>


</div>

<?php endif; ?>



<div class="dashboard-section">


<a href="<?php echo e(route('pemesanan.create')); ?>"
   class="btn-primary">

    <i class="bi bi-plus-circle"></i>

    Tambah Pemesanan

</a>



<table class="data-table">


<thead>

<tr>

<th>
Kode
</th>

<th>
Kegiatan
</th>

<th>
Ruangan
</th>

<th>
Tanggal
</th>

<th>
Waktu
</th>

<th>
Status
</th>

</tr>

</thead>



<tbody>


<?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>


<tr>


<td>

<strong class="text-code">

<?php echo e($item->kode_pemesanan); ?>


</strong>

</td>



<td>

<?php echo e($item->judul_kegiatan); ?>


</td>



<td>

<?php echo e($item->ruangan->nama_ruangan); ?>


<br>

<small>

<?php echo e($item->layout->nama_layout); ?>


</small>

</td>



<td>

<?php echo e($item->tanggal_kegiatan->format('d-m-Y')); ?>


</td>



<td>

<?php echo e($item->waktu_mulai); ?>


-

<?php echo e($item->waktu_selesai); ?>


</td>



<td>


<?php if($item->status->value == 'Pending'): ?>


<span class="badge badge-warning">

<i class="bi bi-clock"></i>

Pending

</span>



<?php elseif($item->status->value == 'Disetujui'): ?>


<span class="badge badge-success">

<i class="bi bi-check-circle"></i>

Disetujui

</span>



<?php elseif($item->status->value == 'Ditolak'): ?>


<span class="badge badge-danger">

<i class="bi bi-x-circle"></i>

Ditolak

</span>



<?php else: ?>


<span class="badge">

<i class="bi bi-dash-circle"></i>

Cancel

</span>



<?php endif; ?>


</td>


</tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>


<tr>

<td colspan="6">


<div class="empty-state">

<i class="bi bi-inbox"></i>


<p>

Belum ada pengajuan.

</p>


</div>


</td>

</tr>


<?php endif; ?>


</tbody>


</table>



<?php echo e($pemesanan->links()); ?>



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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/pemesanan/index.blade.php ENDPATH**/ ?>