<x-app-layout>


<div class="dashboard-header">

<h1>
Data Layout Ruangan
</h1>


</div>



<div class="dashboard-section">


<a href="{{ route('admin.layout.create') }}"
class="btn-primary">

Tambah Layout

</a>



<table class="data-table">


<thead>

<tr>

<th>
Ruangan
</th>

<th>
Layout
</th>

<th>
Kapasitas
</th>

<th>
Action
</th>

</tr>

</thead>


<tbody>


@foreach($layouts as $item)


<tr>


<td>
{{ $item->ruangan->nama_ruangan }}
</td>


<td>
{{ $item->nama_layout }}
</td>


<td>
{{ $item->kapasitas_layout }}
</td>


<td>

<a href="{{ route(
'admin.layout.edit',
$item
) }}">
Edit
</a>


<form method="POST"
action="{{ route(
'admin.layout.destroy',
$item
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


</div>


</x-app-layout>