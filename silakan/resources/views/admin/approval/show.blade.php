<x-app-layout>

<div class="dashboard-header">

    <h1>
        Detail Pemesanan
    </h1>

    <p>
        Review informasi pengajuan penggunaan ruangan sebelum persetujuan.
    </p>

</div>


<div class="detail-container">


<div class="detail-card">

    <div class="detail-title">

        <i class="bi bi-person-circle"></i>

        Informasi Pemohon

    </div>


    <div class="detail-grid">


        <div>
            <label>
                Nama Pemohon
            </label>

            <p>
                {{ $pemesanan->user->name }}
            </p>
        </div>


        <div>
            <label>
                Kode Pemesanan
            </label>

            <p>
                {{ $pemesanan->kode_pemesanan }}
            </p>
        </div>


    </div>


</div>



<div class="detail-card">


    <div class="detail-title">

        <i class="bi bi-calendar-event"></i>

        Informasi Kegiatan

    </div>



    <div class="detail-grid">


        <div>

            <label>
                Judul Kegiatan
            </label>

            <p>
                {{ $pemesanan->judul_kegiatan }}
            </p>

        </div>



        <div>

            <label>
                PIC Kegiatan
            </label>

            <p>
                {{ $pemesanan->pic_kegiatan }}
            </p>

        </div>



        <div>

            <label>
                Jenis PIC
            </label>

            <p>
                {{ $pemesanan->jenis_pic }}
            </p>

        </div>



        <div>

            <label>
                Jumlah Tamu
            </label>

            <p>
                {{ $pemesanan->jumlah_tamu }} orang
            </p>

        </div>



        <div>

            <label>
                Tanggal
            </label>

            <p>
                {{ $pemesanan->tanggal_kegiatan->format('d-m-Y') }}
            </p>

        </div>



        <div>

            <label>
                Waktu
            </label>

            <p>
                {{ $pemesanan->waktu_mulai }}
                -
                {{ $pemesanan->waktu_selesai }}
            </p>

        </div>


    </div>


</div>



<div class="detail-card">


    <div class="detail-title">

        <i class="bi bi-building"></i>

        Informasi Ruangan

    </div>



    <div class="detail-grid">


        <div>

            <label>
                Ruangan
            </label>

            <p>
                {{ $pemesanan->ruangan->nama_ruangan }}
            </p>

        </div>



        <div>

            <label>
                Layout
            </label>

            <p>
                {{ $pemesanan->layout->nama_layout }}
            </p>

        </div>


    </div>



    <label>
        Fasilitas
    </label>


    <div class="facility-list">

    @foreach($pemesanan->ruangan->fasilitas as $fasilitas)

        <span>
            <i class="bi bi-check-circle"></i>
            {{ $fasilitas->nama_fasilitas }}
        </span>

    @endforeach

    </div>


    @if($pemesanan->keterangan_layout)

    <div class="layout-note">

        <label>
            Keterangan Layout
        </label>

        <p>
            {{ $pemesanan->keterangan_layout }}
        </p>

    </div>

    @endif


</div>




<div class="approval-action">


<a href="{{ route('admin.approval.index') }}"
class="btn-secondary">

<i class="bi bi-arrow-left"></i>

Kembali

</a>



<form method="POST"
action="{{ route('admin.approval.reject',$pemesanan) }}">

@csrf


<input type="text"
name="alasan_penolakan"
class="reject-input"
placeholder="Alasan penolakan"
required>


<button class="btn-danger">

<i class="bi bi-x-circle"></i>

Tolak

</button>


</form>




<form method="POST"
action="{{ route('admin.approval.approve',$pemesanan) }}">

@csrf


<button class="btn-primary">

<i class="bi bi-check-circle"></i>

Setujui

</button>


</form>


</div>


</div>


</x-app-layout>