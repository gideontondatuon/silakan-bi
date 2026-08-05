<x-app-layout>

<div class="dashboard-header">

    <h1>
        Waiting List Pemesanan
    </h1>

    <p>
        Kelola dan verifikasi pengajuan penggunaan ruangan sebelum proses persetujuan.
    </p>

</div>


<div class="approval-summary">

    <div class="approval-card">

        <div class="approval-icon warning">

            <i class="bi bi-clock-history"></i>

        </div>

        <div>

            <span>
                Menunggu Approval
            </span>

            <strong>
                {{ $pemesanan->total() }}
            </strong>

        </div>

    </div>


    <div class="approval-card">

        <div class="approval-icon blue">

            <i class="bi bi-calendar-event"></i>

        </div>

        <div>

            <span>
                Total Pengajuan
            </span>

            <strong>
                {{ $pemesanan->total() }}
            </strong>

        </div>

    </div>

</div>



<div class="dashboard-section">


<table class="data-table">


<thead>

<tr>

<th>
Kode
</th>

<th>
Pemohon
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
Status
</th>

<th>
Aksi
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

<div class="table-user">

<strong>

{{ $item->user->name }}

</strong>


<small>

{{ $item->user->nama_unit }}

</small>

</div>

</td>



<td>

<div class="table-info">

<strong>

{{ $item->judul_kegiatan }}

</strong>


<small>

PIC:
{{ $item->pic_kegiatan }}

</small>

</div>

</td>



<td>

<div class="table-info">

<strong>

{{ $item->ruangan->nama_ruangan }}

</strong>


<small>

{{ $item->layout->nama_layout }}

</small>

</div>

</td>



<td>

<div class="table-info">

<strong>

{{ $item->tanggal_kegiatan->format('d-m-Y') }}

</strong>


<small>

{{ $item->waktu_mulai }}

-

{{ $item->waktu_selesai }}

</small>

</div>

</td>



<td>

<span class="badge badge-warning">

<i class="bi bi-clock-history"></i>

{{ $item->status->label() }}

</span>

</td>



<td>

<a href="{{ route(
    'admin.approval.show',
    $item
) }}"
class="btn-table btn-detail">

<i class="bi bi-eye"></i>

Detail

</a>

</td>


</tr>


@empty


<tr>

<td colspan="7">


<div class="empty-state">

<i class="bi bi-inbox"></i>


<p>

Tidak ada pengajuan pemesanan.

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