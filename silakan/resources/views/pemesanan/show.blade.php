<x-app-layout>

<div class="dashboard-header">

    <h1>
        Detail Pemesanan
    </h1>

    <p>
        Informasi pengajuan penggunaan ruangan.
    </p>

</div>


<div class="dashboard-section">


<div class="detail-grid">


<div>

<label>
Kode Pemesanan
</label>

<p>
{{ $pemesanan->kode_pemesanan }}
</p>

</div>



<div>

<label>
Status
</label>

<p>

@if($pemesanan->status->value == 'Pending')

<span class="badge badge-warning">

<i class="bi bi-clock"></i>

Pending

</span>


@elseif($pemesanan->status->value == 'Disetujui')

<span class="badge badge-success">

<i class="bi bi-check-circle"></i>

Disetujui

</span>


@else

<span class="badge badge-danger">

<i class="bi bi-x-circle"></i>

Ditolak

</span>


@endif

</p>

</div>



<div>

<label>
Kegiatan
</label>

<p>
{{ $pemesanan->judul_kegiatan }}
</p>

</div>



<div>

<label>
Ruangan
</label>

<p>
{{ $pemesanan->ruangan->nama_ruangan }}
</p>

</div>



<div>

<label>
Tanggal
</label>

<p>
{{ $pemesanan->tanggal_kegiatan->format('d-m-Y') }}
</p>

</div>



<div>

<label>
Waktu
</label>

<p>
{{ $pemesanan->waktu_mulai }}
-
{{ $pemesanan->waktu_selesai }}
</p>

</div>


</div>


@if($pemesanan->catatan_user)


<hr>


<h3>
Catatan
</h3>


<p>
{{ $pemesanan->catatan_user }}
</p>


@endif



</div>


</x-app-layout>