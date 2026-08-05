<x-app-layout>


<div class="dashboard-header">


    <h1>
        Edit Ruangan
    </h1>


    <p>
        Perbarui informasi ruangan
    </p>


</div>




<div class="dashboard-section">



<form method="POST"
      action="{{ route(
        'admin.ruangan.update',
        $ruangan
      ) }}">


@csrf

@method('PUT')



<div class="form-group">


<label>
Nama Ruangan
</label>


<input
    type="text"
    name="nama_ruangan"
    value="{{ old(
        'nama_ruangan',
        $ruangan->nama_ruangan
    ) }}"
    required
>


@error('nama_ruangan')

<span class="form-error">

{{ $message }}

</span>

@enderror


</div>




<div class="form-group">


<label>
Lokasi
</label>


<input
    type="text"
    name="lokasi"
    value="{{ old(
        'lokasi',
        $ruangan->lokasi
    ) }}"
    required
>


@error('lokasi')

<span class="form-error">

{{ $message }}

</span>

@enderror


</div>




<div class="form-group">


<label>
Kapasitas
</label>


<input
    type="number"
    name="kapasitas"
    value="{{ old(
        'kapasitas',
        $ruangan->kapasitas
    ) }}"
    min="1"
    required
>


@error('kapasitas')

<span class="form-error">

{{ $message }}

</span>

@enderror


</div>




<div class="form-group">


    <label>
    Status Ruangan
    </label>


    <select name="status"
            required>


    <option value="aktif"
    @if($ruangan->status == 'aktif')
    selected
    @endif
    >
    Aktif
    </option>


    <option value="nonaktif"
    @if($ruangan->status == 'nonaktif')
    selected
    @endif
    >
    Nonaktif
    </option>


    <option value="perawatan"
    @if($ruangan->status == 'perawatan')
    selected
    @endif
    >
    Perawatan
    </option>


    </select>


</div>



<div class="facility-list">


@foreach($fasilitas as $item)


<label>


<input
type="checkbox"
name="fasilitas[]"
value="{{ $item->id }}"

@if(
$ruangan->fasilitas
->contains($item->id)
)
checked
@endif

>


{{ $item->nama_fasilitas }}


</label>


@endforeach


</div>

<div class="form-action">


<a href="{{ route(
    'admin.ruangan.index'
) }}"
class="btn-secondary">

Kembali

</a>



<button type="submit"
        class="btn-primary">

Update

</button>


</div>




</form>



</div>



</x-app-layout>