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
        Kalender Ruangan
    </h1>

    <p>
        Monitoring jadwal penggunaan ruangan kantor.
    </p>

</div>



<div class="calendar-summary">


    <div class="calendar-stat">

        <div class="calendar-stat-icon">

            <i class="bi bi-building"></i>

        </div>


        <div>

            <span>
                Total Ruangan
            </span>

            <strong>
                <?php echo e(\App\Models\Ruangan::count()); ?>

            </strong>

        </div>

    </div>



    <div class="calendar-stat">

        <div class="calendar-stat-icon">

            <i class="bi bi-calendar-check"></i>

        </div>


        <div>

            <span>
                Jadwal Aktif
            </span>

            <strong>
                <?php echo e(\App\Models\Pemesanan::approved()->count()); ?>

            </strong>

        </div>

    </div>



    <div class="calendar-stat">

        <div class="calendar-stat-icon">

            <i class="bi bi-clock"></i>

        </div>


        <div>

            <span>
                Hari Ini
            </span>

            <strong>
                <?php echo e(\App\Models\Pemesanan::approved()->today()->count()); ?>

            </strong>

        </div>

    </div>


</div>





<div class="calendar-layout">


<div class="calendar-main">


<div id="calendar"></div>


</div>




<div class="calendar-sidebar">


<h3>
Jadwal Hari Ini
</h3>


<?php

$todaySchedule =
\App\Models\Pemesanan::with('ruangan')
->approved()
->today()
->get();

?>



<?php if($todaySchedule->count()): ?>


<?php $__currentLoopData = $todaySchedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


<div class="today-card">


<i class="bi bi-building"></i>


<div>

<strong>
<?php echo e($item->ruangan->nama_ruangan); ?>

</strong>


<span>
<?php echo e($item->waktu_mulai); ?>

-
<?php echo e($item->waktu_selesai); ?>

</span>


<small>
<?php echo e($item->judul_kegiatan); ?>

</small>


</div>


</div>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php else: ?>


<div class="empty-schedule">

<i class="bi bi-calendar-x"></i>

<p>
Tidak ada jadwal hari ini.
</p>

</div>


<?php endif; ?>



</div>


</div>





<div id="modalKegiatan"
class="calendar-modal">


<div class="calendar-modal-content">


<div class="modal-header">


<h2>
Detail Pemakaian
</h2>


<button
onclick="
document.getElementById('modalKegiatan').style.display='none'
">

×


</button>


</div>



<div id="detailKegiatan"
class="modal-body">


</div>


</div>


</div>






<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<style>
.fc-day-sat, .fc-day-sun {
    background-color: rgba(254, 242, 242, 0.5) !important;
}
.fc-day-sat .fc-daygrid-day-number, .fc-day-sun .fc-daygrid-day-number {
    color: #dc2626 !important;
    font-weight: 800;
}
.fc-col-header-cell.fc-day-sat, .fc-col-header-cell.fc-day-sun {
    background-color: #fee2e2 !important;
    color: #991b1b !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'id',
        height: 700,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu'
        },
        events: "<?php echo e(route('kalender.events')); ?>",
        eventDidMount: function(info) {
            if (info.event.extendedProps.is_nasional !== undefined) {
                info.el.style.backgroundColor = '#ef4444';
                info.el.style.borderColor = '#dc2626';
                info.el.style.color = '#ffffff';
                info.el.style.fontWeight = '700';
            }
        },
        eventClick: function(info) {
            document.getElementById('modalKegiatan').style.display = 'flex';
            const props = info.event.extendedProps;

            if (props.kategori !== undefined || props.is_nasional !== undefined) {
                const isCuti = props.kategori === 'cuti_bersama';
                const isInternal = props.kategori === 'internal';
                const badgeClass = isCuti ? 'badge-warning' : (isInternal ? 'badge-info' : 'badge-danger');
                const labelText = props.kategori_label || (isCuti ? 'Cuti Bersama' : 'Hari Libur Nasional');
                const icon = isCuti ? '🏖️' : (isInternal ? '🏛️' : '🚩');

                document.getElementById('detailKegiatan').innerHTML = `
                    <div class="modal-item">
                        <label>${labelText}</label>
                        <p style="color:#003b73;font-weight:700;font-size:16px;">${icon} ${props.keterangan}</p>
                    </div>
                    <div class="modal-item">
                        <label>Tanggal</label>
                        <p>${info.event.startStr}</p>
                    </div>
                    <div class="modal-item">
                        <label>Kategori</label>
                        <p><span class="badge ${badgeClass}">${labelText}</span></p>
                    </div>
                `;
            } else {
                document.getElementById('detailKegiatan').innerHTML = `
                    <div class="modal-item">
                        <label>Kegiatan</label>
                        <p style="color:#005baa;font-weight:700;">${info.event.title}</p>
                    </div>
                    <div class="modal-item">
                        <label>Ruangan</label>
                        <p>${props.ruangan || '-'}</p>
                    </div>
                    <div class="modal-item">
                        <label>Layout</label>
                        <p>${props.layout || '-'}</p>
                    </div>
                    <div class="modal-item">
                        <label>Waktu</label>
                        <p><i class="bi bi-clock"></i> ${props.waktu || '-'}</p>
                    </div>
                    <div class="modal-item">
                        <label>PIC Kegiatan</label>
                        <p>${props.pic || '-'}</p>
                    </div>
                    <div class="modal-item">
                        <label>Pemohon</label>
                        <p>${props.pemohon || '-'}</p>
                    </div>
                `;
            }
        }
    });

    calendar.render();
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/kalender/index.blade.php ENDPATH**/ ?>