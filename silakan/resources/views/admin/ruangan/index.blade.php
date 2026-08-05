<x-app-layout>


<h1>
Data Ruangan
</h1>


<a href="{{ route('admin.ruangan.create') }}">
Tambah Ruangan
</a>



<table class="data-table">


<thead>

<tr>

<th>
Nama Ruangan
</th>


<th>
Lokasi
</th>


<th>
Kapasitas
</th>


<th>
Status
</th>


<th>
Action
</th>


</tr>

</thead>



<tbody>


@foreach($ruangans as $ruangan)

<tr>


<td>
{{ $ruangan->nama_ruangan }}
</td>


<td>
{{ $ruangan->lokasi }}
</td>


<td>
{{ $ruangan->kapasitas }}
</td>


<td>
{{ ucfirst($ruangan->status) }}
</td>


<td>

<a href="{{ route(
'admin.ruangan.edit',
$ruangan
) }}">
Edit
</a>


<form method="POST"
action="{{ route(
'admin.ruangan.destroy',
$ruangan
) }}"
style="display:inline">


@csrf

@method('DELETE')


<button>
Hapus
</button>


</form>


</td>


</tr>


@endforeach


</tbody>


<tbody>


@foreach($ruangans as $ruangan)

<tr>

<td>
{{ $ruangan->nama_ruangan }}
</td>


<td>
{{ $ruangan->lokasi }}
</td>


<td>
{{ $ruangan->kapasitas }}
</td>


<td>

<a href="{{ route(
'admin.ruangan.edit',
$ruangan
) }}">
Edit
</a>


<form method="POST"
action="{{ route(
'admin.ruangan.destroy',
$ruangan
) }}"
style="display:inline">


@csrf

@method('DELETE')


<button>
Hapus
</button>


</form>


</td>


</tr>


@endforeach


</tbody>


</table>


</x-app-layout>