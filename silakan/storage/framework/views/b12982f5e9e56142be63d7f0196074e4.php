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
        Laporan Penggunaan Ruangan
    </h1>

    <p>
        Rekap penggunaan ruangan berdasarkan pemesanan yang telah disetujui.
    </p>

</div>


<div class="dashboard-section report-filter">

<form method="GET"
      action="<?php echo e(route('admin.laporan.index')); ?>"
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
Ruangan
</label>

<select name="ruangan_id">

<option value="">
Semua Ruangan
</option>

<?php $__currentLoopData = $ruangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<option value="<?php echo e($item->id); ?>"
<?php if(request('ruangan_id') == $item->id): ?>
selected
<?php endif; ?>
>

<?php echo e($item->nama_ruangan); ?>


</option>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</select>

</div>


<div class="filter-action">


<button class="btn-primary">

<i class="bi bi-search"></i>

Tampilkan

</button>



<a href="<?php echo e(route('admin.laporan.index')); ?>"
class="btn-secondary">

Reset

</a>



<a href="<?php echo e(route('admin.laporan.export.excel')); ?>"
class="btn-export btn-excel">

<i class="bi bi-file-earmark-excel"></i>

Export Excel

</a>



<a href="<?php echo e(route('admin.laporan.export.pdf', request()->query())); ?>"
class="btn-export btn-pdf">

<i class="bi bi-file-earmark-pdf"></i>

Export PDF

</a>


</div>

</form>

</div>



<div class="report-stat-grid">


<div class="report-stat-card">

<div class="stat-header">

<div class="stat-icon">

<i class="bi bi-calendar-check"></i>

</div>


<div class="stat-title">
Total Kegiatan
</div>

</div>


<div class="stat-value">

<?php echo e($totalKegiatan); ?>


</div>


<div class="stat-footer">
Kegiatan disetujui
</div>

</div>




<div class="report-stat-card">

<div class="stat-header">

<div class="stat-icon">

<i class="bi bi-clock-history"></i>

</div>


<div class="stat-title">
Total Jam
</div>

</div>


<div class="stat-value">

<?php echo e($totalJam); ?>


</div>


<div class="stat-footer">
Jam penggunaan ruangan
</div>

</div>


</div>




<div class="dashboard-section">

<h2>
Statistik Penggunaan Ruangan
</h2>


<div class="chart-container">

<canvas id="ruanganChart"></canvas>

</div>


</div>




<div class="dashboard-section">


<table class="data-table">

<thead>

<tr>

<th>Kode</th>

<th>Kegiatan</th>

<th>Pemohon</th>

<th>Ruangan</th>

<th>Tanggal</th>

<th>Durasi</th>

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
<?php echo e($item->user->name); ?>

</td>


<td>
<?php echo e($item->ruangan->nama_ruangan); ?>

</td>


<td>
<?php echo e($item->tanggal_kegiatan->format('d-m-Y')); ?>

</td>


<td>

<span class="badge badge-success">

<i class="bi bi-clock"></i>

<?php echo e($item->durasi_format); ?>


</span>

</td>


</tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

<tr>

<td colspan="6">

<div class="empty-state">

<i class="bi bi-file-earmark-x"></i>

<p>
Belum ada data laporan.
</p>

</div>

</td>

</tr>


<?php endif; ?>


</tbody>

</table>


<?php echo e($pemesanan->links()); ?>



</div>




<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx =
document.getElementById('ruanganChart');


new Chart(ctx, {

type:'bar',

data:{

labels:[

<?php $__currentLoopData = $statRuangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

"<?php echo e($item->ruangan->nama_ruangan); ?>",

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

],


datasets:[{

label:'Jumlah Penggunaan',

data:[

<?php $__currentLoopData = $statRuangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<?php echo e($item->total); ?>,

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

]

}]

},


options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{

display:false

}

}

}

});

</script>

<?php $__env->stopPush(); ?>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/laporan/index.blade.php ENDPATH**/ ?>