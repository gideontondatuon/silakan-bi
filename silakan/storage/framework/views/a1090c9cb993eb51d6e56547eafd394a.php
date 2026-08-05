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
        Tambah User
    </h1>

</div>


<div class="dashboard-section">


<form method="POST"
      action="<?php echo e(route('admin.users.store')); ?>">

<?php echo csrf_field(); ?>


<label>
Username
</label>

<input type="text"
       name="username"
       required>



<label>
Nama
</label>

<input type="text"
       name="name">



<label>
Email
</label>

<input type="email"
       name="email">



<label>
Password
</label>

<input type="password"
       name="password"
       required>



<label>
Nama Unit
</label>

<input type="text"
       name="nama_unit"
       required>



<label>
Kode Unit
</label>

<input type="text"
       name="kode_unit"
       required>



<label>
Role
</label>

<select name="role">


<option value="user">
USER
</option>


<option value="admin">
ADMIN
</option>


</select>



<button class="btn-primary">

Simpan

</button>


</form>


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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/users/create.blade.php ENDPATH**/ ?>