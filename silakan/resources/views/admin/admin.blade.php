<x-app-layout>


<div class="dashboard-header">


    <h1>
        Dashboard SILAKAN
    </h1>


    <p>
        Selamat datang,
        {{ auth()->user()->name }}
    </p>


</div>



<div class="stat-grid">


    <x-stat-card
        title="Total Ruangan"
        :value="$totalRuangan"
    />


    <x-stat-card
        title="Menunggu Approval"
        :value="$waitingApproval"
    />


    <x-stat-card
        title="Kegiatan Hari Ini"
        :value="$kegiatanHariIni"
    />


    <x-stat-card
        title="Sedang Berlangsung"
        :value="$kegiatanBerlangsung->count()"
    />


</div>



<div class="dashboard-section">


<h2>
    Waiting List
</h2>



<table class="data-table">


<thead>

<tr>

<th>
Kode
</th>

<th>
Kegiatan
</th>

<th>
Unit
</th>

<th>
Ruangan
</th>

<th>
Status
</th>

</tr>

</thead>


<tbody>


@forelse($waitingList as $item)


<tr>

<td>
{{ $item->kode_pemesanan }}
</td>


<td>
{{ $item->judul_kegiatan }}
</td>


<td>
{{ $item->user->nama_unit }}
</td>


<td>
{{ $item->ruangan->nama_ruangan }}
</td>


<td>

{{ $item->display_status }}

</td>


</tr>


@empty


<tr>

<td colspan="5">

Belum ada pemesanan menunggu approval.

</td>

</tr>


@endforelse


</tbody>


</table>


</div>




<div class="dashboard-section">


<h2>
Kegiatan Berlangsung
</h2>



@forelse(
    $kegiatanBerlangsung
    as $item
)


<div class="activity-card">


<strong>
{{ $item->ruangan->nama_ruangan }}
</strong>


<p>
{{ $item->judul_kegiatan }}
</p>


<span>

{{ $item->waktu_mulai }}

-

{{ $item->waktu_selesai }}

</span>


</div>


@empty


<p>
Tidak ada kegiatan berlangsung.
</p>


@endforelse


</div>


</x-app-layout>