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
        Data User
    </h1>

    <p>
        Manajemen akun pengguna SILAKAN.
    </p>

</div>


<div class="dashboard-section">


<a href="<?php echo e(route('admin.users.create')); ?>"
   class="btn-primary">

    Tambah User

</a>


<table class="data-table">

<thead>

<tr>

<th>
No
</th>

<th>
Username
</th>

<th>
Nama
</th>

<th>
Unit
</th>

<th>
Role
</th>

<th>
Aksi
</th>

</tr>

</thead>


<tbody>


<?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>


<tr>

<td>
<?php echo e($loop->iteration); ?>

</td>


<td>
<?php echo e($user->username); ?>

</td>


<td>
<?php echo e($user->name ?? '-'); ?>

</td>


<td>
<?php echo e($user->nama_unit); ?>

</td>


<td>
    <?php echo e(strtoupper($user->role->value)); ?>

</td>


<td>


<a href="<?php echo e(route(
    'admin.users.edit',
    $user
)); ?>">

Edit

</a>



<form method="POST"
      action="<?php echo e(route(
          'admin.users.destroy',
          $user
      )); ?>"
      style="display:inline">

    <?php echo csrf_field(); ?>

    <?php echo method_field('DELETE'); ?>


    <button type="submit">

        Hapus

    </button>

</form>


</td>

</tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>


<tr>

<td colspan="6">

Belum ada data user.

</td>

</tr>


<?php endif; ?>


</tbody>


</table>


<?php echo e($users->links()); ?>



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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/users/index.blade.php ENDPATH**/ ?>