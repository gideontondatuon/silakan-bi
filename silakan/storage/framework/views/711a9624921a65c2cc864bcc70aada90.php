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

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css"
rel="stylesheet">

<?php $__env->stopPush(); ?>





<?php $__env->startPush('scripts'); ?>


<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>



<script>


document.addEventListener(
'DOMContentLoaded',
function(){


let calendar =
new FullCalendar.Calendar(

document.getElementById('calendar'),

{


initialView:
'dayGridMonth',


locale:
'id',


height:
700,


headerToolbar:{

left:
'prev,next',

center:
'title',

right:
'today dayGridMonth,timeGridWeek'

},



buttonText:{

today:
'Hari Ini',

month:
'Bulan',

week:
'Minggu'

},



events:
"<?php echo e(route('kalender.events')); ?>",



eventDidMount:function(info){


    info.el.style.backgroundColor =
        '#16a34a';


    info.el.style.borderColor =
        '#16a34a';


    info.el.style.color =
        '#ffffff';


},


eventClick:function(info){


document.getElementById(
'modalKegiatan'
).style.display='flex';



document.getElementById(
'detailKegiatan'
).innerHTML=`

<div class="modal-item">

<label>
Kegiatan
</label>

<p>
${info.event.title}
</p>

</div>



<div class="modal-item">

<label>
Ruangan
</label>

<p>
${info.event.extendedProps.ruangan}
</p>

</div>



<div class="modal-item">

<label>
Layout
</label>

<p>
${info.event.extendedProps.layout}
</p>

</div>



<div class="modal-item">

<label>
PIC
</label>

<p>
${info.event.extendedProps.pic}
</p>

</div>



<div class="modal-item">

<label>
Pemohon
</label>

<p>
${info.event.extendedProps.pemohon}
</p>

</div>

`;



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