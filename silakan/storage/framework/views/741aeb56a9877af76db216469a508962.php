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
        Tambah Layout Ruangan
    </h1>

    <p>
        Tambahkan variasi layout pada ruangan
    </p>

</div>



<div class="dashboard-section">


<form method="POST"
action="<?php echo e(route('admin.layout.store')); ?>">


<?php echo csrf_field(); ?>



<div class="form-group">


<label>
Ruangan
</label>


<select name="ruangan_id" required>


<option value="">
-- Pilih Ruangan --
</option>



<?php $__currentLoopData = $ruangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


<option value="<?php echo e($item->id); ?>"
<?php echo e(old('ruangan_id') == $item->id ? 'selected' : ''); ?>

>

<?php echo e($item->nama_ruangan); ?>


</option>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</select>



<?php $__errorArgs = ['ruangan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

<span class="form-error">
<?php echo e($message); ?>

</span>

<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


</div>




<div class="form-group">


<label>
Nama Layout
</label>


<input
type="text"
name="nama_layout"
value="<?php echo e(old('nama_layout')); ?>"
required
>


<?php $__errorArgs = ['nama_layout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

<span class="form-error">
<?php echo e($message); ?>

</span>

<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


</div>




<div class="form-group">


<label>
Kapasitas Layout
</label>


<input
type="number"
name="kapasitas_layout"
value="<?php echo e(old('kapasitas_layout')); ?>"
min="1"
required
>


<?php $__errorArgs = ['kapasitas_layout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

<span class="form-error">
<?php echo e($message); ?>

</span>

<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


</div>




<div class="form-action">


<a href="<?php echo e(route('admin.layout.index')); ?>"
class="btn-secondary">

Kembali

</a>



<button class="btn-primary">

Simpan

</button>


</div>



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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/layout/create.blade.php ENDPATH**/ ?>