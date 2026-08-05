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
        Dashboard SILAKAN
    </h1>

    <p>
        Selamat datang,
        <?php echo e(auth()->user()->name); ?>

    </p>

</div>

<div class="stat-grid">

    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Total Ruangan','value' => $totalRuangan] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'building']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $attributes = $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $component = $__componentOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>


    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Menunggu Approval','value' => $waitingApproval] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'clock-history']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $attributes = $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $component = $__componentOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>


    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Kegiatan Hari Ini','value' => $kegiatanHariIni] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar-check']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $attributes = $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $component = $__componentOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>


    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Sedang Berlangsung','value' => $kegiatanBerlangsung->count()] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'play-circle']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $attributes = $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $component = $__componentOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>

</div>


<div class="dashboard-section">

    <h2>
        Waiting List
    </h2>


    <table class="data-table">

        <thead>

            <tr>
                <th>Kode</th>
                <th>Kegiatan</th>
                <th>Unit</th>
                <th>Ruangan</th>
                <th>Status</th>
            </tr>

        </thead>


        <tbody>

        <?php $__empty_1 = true; $__currentLoopData = $waitingList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <tr>

                <td>
                    <?php echo e($item->kode_pemesanan); ?>

                </td>

                <td>
                    <?php echo e($item->judul_kegiatan); ?>

                </td>

                <td>
                    <?php echo e($item->user->nama_unit); ?>

                </td>

                <td>
                    <?php echo e($item->ruangan->nama_ruangan); ?>

                </td>

                <td>

                    <span class="badge badge-warning">
                        <?php echo e($item->status->label()); ?>

                    </span>

                </td>

            </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <tr>

                <td colspan="5">
                    Belum ada pemesanan.
                </td>

            </tr>

        <?php endif; ?>

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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>