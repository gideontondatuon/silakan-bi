<x-app-layout>

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
                {{ \App\Models\Ruangan::count() }}
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
                {{ \App\Models\Pemesanan::approved()->count() }}
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
                {{ \App\Models\Pemesanan::approved()->today()->count() }}
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


@php

$todaySchedule =
\App\Models\Pemesanan::with('ruangan')
->approved()
->today()
->get();

@endphp



@if($todaySchedule->count())


@foreach($todaySchedule as $item)


<div class="today-card">


<i class="bi bi-building"></i>


<div>

<strong>
{{ $item->ruangan->nama_ruangan }}
</strong>


<span>
{{ $item->waktu_mulai }}
-
{{ $item->waktu_selesai }}
</span>


<small>
{{ $item->judul_kegiatan }}
</small>


</div>


</div>


@endforeach


@else


<div class="empty-schedule">

<i class="bi bi-calendar-x"></i>

<p>
Tidak ada jadwal hari ini.
</p>

</div>


@endif



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






@push('styles')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css"
rel="stylesheet">

@endpush





@push('scripts')


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
"{{ route('kalender.events') }}",



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


@endpush


</x-app-layout>