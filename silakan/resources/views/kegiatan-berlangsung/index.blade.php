<x-app-layout>


<div class="dashboard-header">


<h1>
Kegiatan Berlangsung
</h1>


<p>
Monitoring penggunaan ruangan yang sedang aktif.
</p>


</div>





<div class="dashboard-section">



@if($kegiatan->count())


<div class="live-grid">



@foreach($kegiatan as $item)



<div class="live-card">


<div class="live-header">


<div class="live-status">

<span></span>

Sedang Berlangsung

</div>



<i class="bi bi-broadcast-pin"></i>


</div>




<h3>

{{ $item->ruangan->nama_ruangan }}

</h3>



<p class="live-title">

{{ $item->judul_kegiatan }}

</p>




<div class="live-info">


<div>

<i class="bi bi-clock"></i>

{{ $item->waktu_mulai }}

-

{{ $item->waktu_selesai }}

</div>



<div>

<i class="bi bi-person"></i>

{{ $item->user->name }}

</div>



<div>

<i class="bi bi-layout-text-window"></i>

{{ $item->layout->nama_layout }}

</div>


</div>



</div>



@endforeach



</div>



@else


<div class="empty-state">


<i class="bi bi-calendar-x"></i>


<p>
Tidak ada kegiatan yang sedang berlangsung.
</p>


</div>


@endif



</div>



</x-app-layout>