<x-app-layout>

<div class="dashboard-header">

    <h1>
        Pemesanan Saya
    </h1>

    <p>
        Daftar pengajuan penggunaan ruangan.
    </p>

</div>


@if(session('success'))

<div class="alert-success">

    <i class="bi bi-check-circle"></i>

    {{ session('success') }}

</div>

@endif


@if(session('error'))

<div class="alert-error">

    <i class="bi bi-exclamation-circle"></i>

    {{ session('error') }}

</div>

@endif



<div class="dashboard-section">


<a href="{{ route('pemesanan.create') }}"
   class="btn-primary">

    <i class="bi bi-plus-circle"></i>

    Tambah Pemesanan

</a>



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
Ruangan
</th>

<th>
Tanggal
</th>

<th>
Waktu
</th>

<th>
Status
</th>

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

{{ $item->ruangan->nama_ruangan }}

<br>

<small>

{{ $item->layout->nama_layout }}

</small>

</td>



<td>

{{ $item->tanggal_kegiatan->format('d-m-Y') }}

</td>



<td>

{{ $item->waktu_mulai }}

-

{{ $item->waktu_selesai }}

</td>



<td>


@if($item->status->value == 'Pending')


<span class="badge badge-warning">

<i class="bi bi-clock"></i>

Pending

</span>



@elseif($item->status->value == 'Disetujui')


<span class="badge badge-success">

<i class="bi bi-check-circle"></i>

Disetujui

</span>



@elseif($item->status->value == 'Ditolak')


<span class="badge badge-danger">

<i class="bi bi-x-circle"></i>

Ditolak

</span>



@else


<span class="badge">

<i class="bi bi-dash-circle"></i>

Cancel

</span>



@endif


</td>


</tr>


@empty


<tr>

<td colspan="6">


<div class="empty-state">

<i class="bi bi-inbox"></i>


<p>

Belum ada pengajuan.

</p>


</div>


</td>

</tr>


@endforelse


</tbody>


</table>



{{ $pemesanan->links() }}


</div>


</x-app-layout>