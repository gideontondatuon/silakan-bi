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


<h1>
Data Ruangan
</h1>


<a href="<?php echo e(route('admin.ruangan.create')); ?>">
Tambah Ruangan
</a>



<table class="data-table">


<thead>

<tr>

<th>
Nama Ruangan
</th>


<th>
Lokasi
</th>


<th>
Kapasitas
</th>


<th>
Status
</th>


<th>
Action
</th>


</tr>

</thead>



<tbody>


<?php $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<tr>


<td>
<?php echo e($ruangan->nama_ruangan); ?>

</td>


<td>
<?php echo e($ruangan->lokasi); ?>

</td>


<td>
<?php echo e($ruangan->kapasitas); ?>

</td>


<td>
<?php echo e(ucfirst($ruangan->status)); ?>

</td>


<td>

<a href="<?php echo e(route(
'admin.ruangan.edit',
$ruangan
)); ?>">
Edit
</a>


<form method="POST"
action="<?php echo e(route(
'admin.ruangan.destroy',
$ruangan
)); ?>"
style="display:inline">


<?php echo csrf_field(); ?>

<?php echo method_field('DELETE'); ?>


<button>
Hapus
</button>


</form>


</td>


</tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</tbody>


<tbody>


<?php $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<tr>

<td>
<?php echo e($ruangan->nama_ruangan); ?>

</td>


<td>
<?php echo e($ruangan->lokasi); ?>

</td>


<td>
<?php echo e($ruangan->kapasitas); ?>

</td>


<td>

<a href="<?php echo e(route(
'admin.ruangan.edit',
$ruangan
)); ?>">
Edit
</a>


<form method="POST"
action="<?php echo e(route(
'admin.ruangan.destroy',
$ruangan
)); ?>"
style="display:inline">


<?php echo csrf_field(); ?>

<?php echo method_field('DELETE'); ?>


<button>
Hapus
</button>


</form>


</td>


</tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</tbody>


</table>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/ruangan/index.blade.php ENDPATH**/ ?>