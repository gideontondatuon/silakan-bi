<x-app-layout>


<div class="dashboard-header">

    <h1>
        Tambah Ruangan
    </h1>

    <p>
        Tambahkan data ruangan baru
    </p>

</div>



<div class="dashboard-section">


<form method="POST"
      action="{{ route('admin.ruangan.store') }}">

    @csrf



    <div class="form-group">

        <label>
            Nama Ruangan
        </label>


        <input
            type="text"
            name="nama_ruangan"
            value="{{ old('nama_ruangan') }}"
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
            value="{{ old('lokasi') }}"
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
            value="{{ old('kapasitas') }}"
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


        <option value="aktif">
        Aktif
        </option>


        <option value="nonaktif">
        Nonaktif
        </option>


        <option value="perawatan">
        Perawatan
        </option>


        </select>


    </div>

    <div class="form-group">

        <label>
            Fasilitas Ruangan
        </label>
        <div class="facility-list">
            @foreach($fasilitas as $item)
        <label>
            <input
            type="checkbox"
            name="fasilitas[]"
            value="{{ $item->id }}"
            >
            {{ $item->nama_fasilitas }}
        </label>
        @endforeach
        </div>

    </div>

    <div class="form-action">


        <a href="{{ route('admin.ruangan.index') }}"
           class="btn-secondary">

            Kembali

        </a>



        <button type="submit"
                class="btn-primary">

            Simpan

        </button>


    </div>



</form>


</div>


</x-app-layout>