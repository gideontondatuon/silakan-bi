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
        Waiting List Pemesanan
    </h1>

    <p>
        Kelola dan verifikasi pengajuan penggunaan ruangan sebelum proses persetujuan.
    </p>

</div>


<div class="approval-summary">

    <div class="approval-card">

        <div class="approval-icon warning">

            <i class="bi bi-clock-history"></i>

        </div>

        <div>

            <span>
                Menunggu Approval
            </span>

            <strong>
                <?php echo e($pemesanan->total()); ?>

            </strong>

        </div>

    </div>


    <div class="approval-card">

        <div class="approval-icon blue">

            <i class="bi bi-calendar-event"></i>

        </div>

        <div>

            <span>
                Total Pengajuan
            </span>

            <strong>
                <?php echo e($pemesanan->total()); ?>

            </strong>

        </div>

    </div>

</div>



<div class="dashboard-section">


<table class="data-table">


<thead>

<tr>

<th>
Kode
</th>

<th>
Pemohon
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
Status
</th>

<th>
Aksi
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

<div class="table-user">

<strong>

<?php echo e($item->user->name); ?>


</strong>


<small>

<?php echo e($item->user->nama_unit); ?>


</small>

</div>

</td>



<td>

<div class="table-info">

<strong>

<?php echo e($item->judul_kegiatan); ?>


</strong>


<small>

PIC:
<?php echo e($item->pic_kegiatan); ?>


</small>

</div>

</td>



<td>

<div class="table-info">

<strong>

<?php echo e($item->ruangan->nama_ruangan); ?>


</strong>


<small>

<?php echo e($item->layout->nama_layout); ?>


</small>

</div>

</td>



<td>

<div class="table-info">

<strong>

<?php echo e($item->tanggal_kegiatan->format('d-m-Y')); ?>


</strong>


<small>

<?php echo e($item->waktu_mulai); ?>


-

<?php echo e($item->waktu_selesai); ?>


</small>

</div>

</td>



<td>

<span class="badge badge-warning">

<i class="bi bi-clock-history"></i>

<?php echo e($item->status->label()); ?>


</span>

</td>



<td>

<a href="<?php echo e(route(
    'admin.approval.show',
    $item
)); ?>"
class="btn-table btn-detail">

<i class="bi bi-eye"></i>

Detail

</a>

</td>


</tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>


<tr>

<td colspan="7">


<div class="empty-state">

<i class="bi bi-inbox"></i>


<p>

Tidak ada pengajuan pemesanan.

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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/approval/index.blade.php ENDPATH**/ ?>