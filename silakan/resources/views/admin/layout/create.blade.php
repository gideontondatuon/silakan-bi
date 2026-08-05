<x-app-layout>


<div class="dashboard-header">

    <h1>
        Tambah Layout Ruangan
    </h1>

    <p>
        Tambahkan variasi layout pada ruangan
    </p>

</div>



<div class="dashboard-section">


<form method="POST"
action="{{ route('admin.layout.store') }}">


@csrf



<div class="form-group">


<label>
Ruangan
</label>


<select name="ruangan_id" required>


<option value="">
-- Pilih Ruangan --
</option>



@foreach($ruangan as $item)


<option value="{{ $item->id }}"
{{ old('ruangan_id') == $item->id ? 'selected' : '' }}
>

{{ $item->nama_ruangan }}

</option>


@endforeach


</select>



@error('ruangan_id')

<span class="form-error">
{{ $message }}
</span>

@enderror


</div>




<div class="form-group">


<label>
Nama Layout
</label>


<input
type="text"
name="nama_layout"
value="{{ old('nama_layout') }}"
required
>


@error('nama_layout')

<span class="form-error">
{{ $message }}
</span>

@enderror


</div>




<div class="form-group">


<label>
Kapasitas Layout
</label>


<input
type="number"
name="kapasitas_layout"
value="{{ old('kapasitas_layout') }}"
min="1"
required
>


@error('kapasitas_layout')

<span class="form-error">
{{ $message }}
</span>

@enderror


</div>




<div class="form-action">


<a href="{{ route('admin.layout.index') }}"
class="btn-secondary">

Kembali

</a>



<button class="btn-primary">

Simpan

</button>


</div>



</form>


</div>


</x-app-layout>