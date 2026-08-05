<x-app-layout>

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
      action="{{ route('admin.laporan.index') }}"
      class="filter-form">

<div>

<label>
Tanggal Mulai
</label>

<input type="date"
name="tanggal_mulai"
value="{{ request('tanggal_mulai') }}">

</div>


<div>

<label>
Tanggal Selesai
</label>

<input type="date"
name="tanggal_selesai"
value="{{ request('tanggal_selesai') }}">

</div>


<div>

<label>
Ruangan
</label>

<select name="ruangan_id">

<option value="">
Semua Ruangan
</option>

@foreach($ruangan as $item)

<option value="{{ $item->id }}"
@if(request('ruangan_id') == $item->id)
selected
@endif
>

{{ $item->nama_ruangan }}

</option>

@endforeach

</select>

</div>


<div class="filter-action">


<button class="btn-primary">

<i class="bi bi-search"></i>

Tampilkan

</button>



<a href="{{ route('admin.laporan.index') }}"
class="btn-secondary">

Reset

</a>



<a href="{{ route('admin.laporan.export.excel') }}"
class="btn-export btn-excel">

<i class="bi bi-file-earmark-excel"></i>

Export Excel

</a>



<a href="{{ route('admin.laporan.export.pdf', request()->query()) }}"
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

{{ $totalKegiatan }}

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

{{ $totalJam }}

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


@forelse($pemesanan as $item)

<tr>


<td>

<strong class="text-code">
{{ $item->kode_pemesanan }}
</strong>

</td>


<td>
{{ $item->judul_kegiatan }}
</td>


<td>
{{ $item->user->name }}
</td>


<td>
{{ $item->ruangan->nama_ruangan }}
</td>


<td>
{{ $item->tanggal_kegiatan->format('d-m-Y') }}
</td>


<td>

<span class="badge badge-success">

<i class="bi bi-clock"></i>

{{ $item->durasi_format }}

</span>

</td>


</tr>


@empty

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


@endforelse


</tbody>

</table>


{{ $pemesanan->links() }}


</div>




@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx =
document.getElementById('ruanganChart');


new Chart(ctx, {

type:'bar',

data:{

labels:[

@foreach($statRuangan as $item)

"{{ $item->ruangan->nama_ruangan }}",

@endforeach

],


datasets:[{

label:'Jumlah Penggunaan',

data:[

@foreach($statRuangan as $item)

{{ $item->total }},

@endforeach

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

@endpush


</x-app-layout>