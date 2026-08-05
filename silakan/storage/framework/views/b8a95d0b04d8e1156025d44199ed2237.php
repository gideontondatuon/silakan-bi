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
Audit Log Sistem
</h1>


<p>
Riwayat aktivitas pengguna dalam sistem SILAKAN.
</p>


</div>




<div class="dashboard-section">


<form method="GET"
class="filter-form">


<div>

<label>
Tanggal Mulai
</label>


<input type="date"
name="tanggal_mulai"
value="<?php echo e(request('tanggal_mulai')); ?>">


</div>




<div>

<label>
Tanggal Selesai
</label>


<input type="date"
name="tanggal_selesai"
value="<?php echo e(request('tanggal_selesai')); ?>">


</div>



<div>

<label>
Modul
</label>


<select name="modul">


<option value="">
Semua Modul
</option>


<option value="Pemesanan">
Pemesanan
</option>


<option value="Approval">
Approval
</option>


<option value="Master Data">
Master Data
</option>


</select>


</div>



<div class="filter-action">


<button class="btn-primary">

<i class="bi bi-search"></i>

Tampilkan

</button>



<a href="<?php echo e(route('admin.audit-log.index')); ?>"
class="btn-secondary">

Reset

</a>


</div>


</form>


</div>







<div class="dashboard-section">


<table class="data-table">


<thead>

<tr>

<th>
Tanggal
</th>


<th>
User
</th>


<th>
Aksi
</th>


<th>
Modul
</th>


<th>
Keterangan
</th>


</tr>


</thead>



<tbody>


<?php $__empty_1 = true; $__currentLoopData = $auditLog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>


<tr>


<td>

<?php echo e($item->created_at->format('d-m-Y H:i')); ?>


</td>



<td>

<?php if($item->user): ?>

<?php echo e($item->user->name); ?>


<?php else: ?>

System

<?php endif; ?>

</td>



<td>

<?php echo e($item->aksi); ?>


</td>



<td>

<span class="badge badge-primary">

<?php echo e($item->modul); ?>


</span>

</td>



<td>

<?php echo e($item->keterangan); ?>


</td>



</tr>



<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>


<tr>

<td colspan="5">


<div class="empty-state">

<i class="bi bi-clock-history"></i>


<p>
Belum ada aktivitas.
</p>


</div>


</td>

</tr>


<?php endif; ?>



</tbody>


</table>




<?php echo e($auditLog->links()); ?>




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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/audit-log/index.blade.php ENDPATH**/ ?>