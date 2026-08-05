<x-app-layout>


<div class="dashboard-header">

<h1>
Edit Layout Ruangan
</h1>

</div>




<div class="dashboard-section">


<form method="POST"
action="{{ route(
'admin.layout.update',
$layout
) }}">


@csrf

@method('PUT')



<div class="form-group">


<label>
Ruangan
</label>


<select name="ruangan_id" required>


@foreach($ruangan as $item)


<option value="{{ $item->id }}"

@if(
$layout->ruangan_id == $item->id
)

selected

@endif

>

{{ $item->nama_ruangan }}

</option>


@endforeach


</select>


</div>




<div class="form-group">


<label>
Nama Layout
</label>


<input
type="text"
name="nama_layout"

value="{{ old(
'nama_layout',
$layout->nama_layout
) }}"

required
>


</div>




<div class="form-group">


<label>
Kapasitas Layout
</label>


<input
type="number"
name="kapasitas_layout"

value="{{ old(
'kapasitas_layout',
$layout->kapasitas_layout
) }}"

min="1"

required
>


</div>




<div class="form-action">


<a href="{{ route(
'admin.layout.index'
) }}"
class="btn-secondary">

Kembali

</a>



<button class="btn-primary">

Update

</button>


</div>



</form>


</div>


</x-app-layout>