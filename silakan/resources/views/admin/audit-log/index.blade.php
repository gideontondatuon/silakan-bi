<x-app-layout>


<div class="dashboard-header">


<h1>
Audit Log Sistem
</h1>


<p>
Riwayat aktivitas pengguna dalam sistem SILAKAN.
</p>


</div>




<div class="dashboard-section">


<form method="GET"
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
Modul
</label>


<select name="modul">


<option value="">
Semua Modul
</option>


<option value="Pemesanan">
Pemesanan
</option>


<option value="Approval">
Approval
</option>


<option value="Master Data">
Master Data
</option>


</select>


</div>



<div class="filter-action">


<button class="btn-primary">

<i class="bi bi-search"></i>

Tampilkan

</button>



<a href="{{ route('admin.audit-log.index') }}"
class="btn-secondary">

Reset

</a>


</div>


</form>


</div>







<div class="dashboard-section">


<table class="data-table">


<thead>

<tr>

<th>
Tanggal
</th>


<th>
User
</th>


<th>
Aksi
</th>


<th>
Modul
</th>


<th>
Keterangan
</th>


</tr>


</thead>



<tbody>


@forelse($auditLog as $item)


<tr>


<td>

{{ $item->created_at->format('d-m-Y H:i') }}

</td>



<td>

@if($item->user)

{{ $item->user->name }}

@else

System

@endif

</td>



<td>

{{ $item->aksi }}

</td>



<td>

<span class="badge badge-primary">

{{ $item->modul }}

</span>

</td>



<td>

{{ $item->keterangan }}

</td>



</tr>



@empty


<tr>

<td colspan="5">


<div class="empty-state">

<i class="bi bi-clock-history"></i>


<p>
Belum ada aktivitas.
</p>


</div>


</td>

</tr>


@endforelse



</tbody>


</table>




{{ $auditLog->links() }}



</div>


</x-app-layout>