<x-app-layout>


<div class="dashboard-header">

<h1>
Data Fasilitas
</h1>


<p>
Kelola fasilitas yang tersedia
</p>


</div>



<div class="dashboard-section">


<a href="{{ route('admin.fasilitas.create') }}"
class="btn-primary">

Tambah Fasilitas

</a>



<table class="data-table">


<thead>

<tr>

<th>
No
</th>


<th>
Nama Fasilitas
</th>


<th>
Action
</th>


</tr>

</thead>



<tbody>


@forelse($fasilitas as $item)


<tr>


<td>
{{ $loop->iteration }}
</td>


<td>
{{ $item->nama_fasilitas }}
</td>



<td>


<a href="{{ route(
'admin.fasilitas.edit',
$item
) }}">

Edit

</a>




<form method="POST"
action="{{ route(
'admin.fasilitas.destroy',
$item
) }}"
style="display:inline">


@csrf

@method('DELETE')


<button type="submit">

Hapus

</button>


</form>


</td>


</tr>



@empty


<tr>

<td colspan="3">

Belum ada data fasilitas

</td>

</tr>


@endforelse



</tbody>


</table>


</div>


</x-app-layout>