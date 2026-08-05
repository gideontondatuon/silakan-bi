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
Kegiatan Berlangsung
</h1>


<p>
Monitoring penggunaan ruangan yang sedang aktif.
</p>


</div>





<div class="dashboard-section">



<?php if($kegiatan->count()): ?>


<div class="live-grid">



<?php $__currentLoopData = $kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>



<div class="live-card">


<div class="live-header">


<div class="live-status">

<span></span>

Sedang Berlangsung

</div>



<i class="bi bi-broadcast-pin"></i>


</div>




<h3>

<?php echo e($item->ruangan->nama_ruangan); ?>


</h3>



<p class="live-title">

<?php echo e($item->judul_kegiatan); ?>


</p>




<div class="live-info">


<div>

<i class="bi bi-clock"></i>

<?php echo e($item->waktu_mulai); ?>


-

<?php echo e($item->waktu_selesai); ?>


</div>



<div>

<i class="bi bi-person"></i>

<?php echo e($item->user->name); ?>


</div>



<div>

<i class="bi bi-layout-text-window"></i>

<?php echo e($item->layout->nama_layout); ?>


</div>


</div>



</div>



<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



</div>



<?php else: ?>


<div class="empty-state">


<i class="bi bi-calendar-x"></i>


<p>
Tidak ada kegiatan yang sedang berlangsung.
</p>


</div>


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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/kegiatan-berlangsung/index.blade.php ENDPATH**/ ?>