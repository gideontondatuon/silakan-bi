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
Data Layout Ruangan
</h1>


</div>



<div class="dashboard-section">


<a href="<?php echo e(route('admin.layout.create')); ?>"
class="btn-primary">

Tambah Layout

</a>



<table class="data-table">


<thead>

<tr>

<th>
Ruangan
</th>

<th>
Layout
</th>

<th>
Kapasitas
</th>

<th>
Action
</th>

</tr>

</thead>


<tbody>


<?php $__currentLoopData = $layouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


<tr>


<td>
<?php echo e($item->ruangan->nama_ruangan); ?>

</td>


<td>
<?php echo e($item->nama_layout); ?>

</td>


<td>
<?php echo e($item->kapasitas_layout); ?>

</td>


<td>

<a href="<?php echo e(route(
'admin.layout.edit',
$item
)); ?>">
Edit
</a>


<form method="POST"
action="<?php echo e(route(
'admin.layout.destroy',
$item
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/layout/index.blade.php ENDPATH**/ ?>