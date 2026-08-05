<x-app-layout>


<div class="dashboard-header">

<h1>
Tambah Fasilitas
</h1>

</div>



<div class="dashboard-section">


<form method="POST"
action="{{ route('admin.fasilitas.store') }}">


@csrf



<div class="form-group">


<label>
Nama Fasilitas
</label>


<input
type="text"
name="nama_fasilitas"
value="{{ old('nama_fasilitas') }}"
required
>


@error('nama_fasilitas')

<span class="form-error">
{{ $message }}
</span>

@enderror


</div>




<div class="form-action">


<a href="{{ route('admin.fasilitas.index') }}"
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